#!/usr/bin/env bash
# Cursor Cloud Agent start: dockerd + Compose stack ready on :8000.
set -euo pipefail

if [ -d /agent/repos/material-pass ]; then
  cd /agent/repos/material-pass || exit $?
fi

if ! docker info >/dev/null 2>&1 && ! sudo docker info >/dev/null 2>&1; then
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
fi
sudo chmod 666 /var/run/docker.sock 2>/dev/null || true
[ -f .env ] || cp .env.example .env

docker compose down --remove-orphans 2>/dev/null || true
docker rm -f material-pass-app material-pass-nginx material-pass-postgres \
  material-pass-redis material-pass-queue material-pass-vite 2>/dev/null || true
docker compose up -d --remove-orphans

for _ in $(seq 1 90); do
  pg=$(docker compose ps postgres --format '{{.Health}}' 2>/dev/null || true)
  rd=$(docker compose ps redis --format '{{.Health}}' 2>/dev/null || true)
  if [ "$pg" = "healthy" ] && [ "$rd" = "healthy" ]; then break; fi
  sleep 2
done

for _ in $(seq 1 90); do
  if docker compose exec -T app test -f vendor/autoload.php 2>/dev/null; then
    if docker compose exec -T app php -r 'exit(0);' >/dev/null 2>&1; then break; fi
  fi
  sleep 2
done

docker compose exec -T app php artisan key:generate --force --no-interaction >/dev/null 2>&1 || true
docker compose exec -T app php artisan migrate --force --no-interaction
docker compose up -d --no-deps nginx
docker compose restart nginx >/dev/null

for _ in $(seq 1 60); do
  code=$(curl -s -o /dev/null -w '%{http_code}' http://127.0.0.1:8000/ || true)
  if [ "$code" = "200" ]; then exit 0; fi
  sleep 2
done

echo "App did not become ready on :8000" >&2
docker compose ps >&2 || true
docker compose logs --tail=40 nginx app >&2 || true
exit 1
