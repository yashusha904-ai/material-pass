<?php

namespace App\Enums;

enum MaterialPassApprovalStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Skipped = 'skipped';
}
