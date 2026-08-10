<?php

namespace App\Enums;

enum TaskStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case IN_PROGRESS = 'in_progress';
    case CANCELLED = 'cancelled';
}
