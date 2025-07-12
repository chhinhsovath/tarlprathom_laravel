<?php

namespace App\Policies;

use App\Models\Assessment;
use App\Models\User;

class AssessmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'mentor', 'teacher', 'viewer']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Assessment $assessment): bool
    {
        if ($user->isAdmin() || $user->isViewer() || $user->isMentor()) {
            return true;
        }

        if ($user->isTeacher() && $assessment->student->school_id === $user->school_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'teacher', 'mentor']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Assessment $assessment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher() && $assessment->student->school_id === $user->school_id) {
            return true;
        }

        if ($user->isMentor()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Assessment $assessment): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Assessment $assessment): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Assessment $assessment): bool
    {
        return $user->isAdmin();
    }
}
