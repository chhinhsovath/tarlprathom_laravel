<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy
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
    public function view(User $user, Student $student): bool
    {
        if ($user->isAdmin() || $user->isViewer()) {
            return true;
        }

        // Teachers can view students from their school
        if ($user->isTeacher() && $user->school_id === $student->school_id) {
            return true;
        }

        // Mentors can view students from their assigned schools
        if ($user->isMentor() && $user->canAccessStudent($student->id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'teacher']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Student $student): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Teachers can update students from their school
        if ($user->isTeacher() && $user->school_id === $student->school_id) {
            return true;
        }

        // Mentors can update students from their assigned schools
        if ($user->isMentor() && $user->canAccessStudent($student->id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Student $student): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Student $student): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Student $student): bool
    {
        return $user->isAdmin();
    }
}
