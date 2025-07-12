<?php

namespace App\Policies;

use App\Models\MentoringVisit;
use App\Models\User;

class MentoringVisitPolicy
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
    public function view(User $user, MentoringVisit $mentoringVisit): bool
    {
        if ($user->isAdmin() || $user->isViewer()) {
            return true;
        }

        if ($user->isMentor() && $mentoringVisit->mentor_id === $user->id) {
            return true;
        }

        if ($user->isTeacher() && $mentoringVisit->teacher_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'mentor']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MentoringVisit $mentoringVisit): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isMentor() && $mentoringVisit->mentor_id === $user->id) {
            // Mentors can only update their own visits within 7 days
            return $mentoringVisit->visit_date->diffInDays(now()) <= 7;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MentoringVisit $mentoringVisit): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MentoringVisit $mentoringVisit): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MentoringVisit $mentoringVisit): bool
    {
        return $user->isAdmin();
    }
}
