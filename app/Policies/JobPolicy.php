<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Job;

class JobPolicy
{
    public function viewAny(?User $user): bool
    {
        return true; // anyone (even guests) can see list if you want
    }

    public function create(User $user): bool
    {
        // Only admin can create
        return (int) $user->role_id === 1;
        // or: return $user->hasRole('admin');
    }

    // (You can add update/delete later similarly)
}
