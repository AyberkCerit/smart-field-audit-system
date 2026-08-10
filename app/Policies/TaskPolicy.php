<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('field_personnel');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        // Saha personeli sadece kendine atanmış veya henüz havuza atanmamış görevleri görebilir.
        return $user->hasRole('field_personnel') && 
               ($task->assigned_to === $user->id || $task->assigned_to === null);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Managed by before()
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        return false;
    }

    /**
     * Determine whether the user can claim the model.
     */
    public function claim(User $user, Task $task): bool
    {
        return $user->hasRole('field_personnel') && 
               $task->status === 'pending' && 
               $task->assigned_to === null;
    }

    /**
     * Determine whether the user can complete the model.
     */
    public function complete(User $user, Task $task): bool
    {
        return $user->hasRole('field_personnel') && 
               $task->status === 'pending' && 
               $task->assigned_to === $user->id;
    }
}
