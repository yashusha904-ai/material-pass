#!/usr/bin/env bash
# Cursor Cloud Agent install: idempotent deps + compose image build.
set -euo pipefail
export DEBIAN_FRONTEND=noninteractive

if [ -d /agent/repos/material-pass ]; then
  cd /agent/repos/material-pass || exit $?
fi

echo "cwd=$(pwd) user=$(id -un)"

ensure_dockerd() {
  if docker info >/dev/null 2>&1 || sudo docker info >/dev/null 2>&1; then
    sudo chmod 666 /var/run/docker.sock 2>/dev/null || true
    return 0
  fi
  sudo mkdir -p /etc/docker
  [ -f /etc/docker/daemon.json ] || printf '%s\n' '{' '  "storage-driver": "fuse-overlayfs"' '}' | sudo tee /etc/docker/daemon.json >/dev/null
  sudo update-alternatives --set iptables /usr/sbin/iptables-legacy || true
  sudo update-alternatives --set ip6tables /usr/sbin/ip6tables-legacy || true
  sudo pkill -9 dockerd 2>/dev/null || true
  sudo pkill -9 containerd 2>/dev/null || true
  sleep 1
  sudo rm -f /var/run/docker.pid /run/docker.pid
  sudo dockerd --host=unix:///var/run/docker.sock >/tmp/dockerd.log 2>&1 &
  for _ in $(seq 1 90); do
    if sudo docker info >/dev/null 2>&1; then break; fi
    sleep 1
  done
  sudo chmod 666 /var/run/docker.sock 2>/dev/null || true
}

ensure_dockerd
[ -f .env ] || cp .env.example .env

# Stop services that may lock bind-mounted vendor/node_modules
docker compose stop vite app queue nginx 2>/dev/null || true
sudo umount vendor 2>/dev/null || true
sudo umount node_modules 2>/dev/null || true

# Real vendor/node_modules in the checkout (portable Composer baseDir).
# Do not run composer inside the nested Docker build context — fuse-overlayfs
# bind mounts break composer package deletes.
if [ ! -f vendor/autoload.php ]; then
  sudo rm -rf vendor
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi
if [ ! -d node_modules/vite ]; then
  sudo rm -rf node_modules
  npm ci --no-fund --no-audit
fi

php -r 'require "vendor/autoload.php"; exit(class_exists("App\\Providers\\AppServiceProvider")?0:1);'
php artisan package:discover --ansi || true
docker compose build
docker compose down --remove-orphans 2>/dev/null || true
docker rm -f material-pass-app material-pass-nginx material-pass-postgres \
  material-pass-redis material-pass-queue material-pass-vite 2>/dev/null || true
