<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AcademicCalendar;
use Illuminate\Auth\Access\HandlesAuthorization;

class AcademicCalendarPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view calendar
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AcademicCalendar $academicCalendar): bool
    {
        // All authenticated users can view calendar events
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admin can create calendar events
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AcademicCalendar $academicCalendar): bool
    {
        // Only admin can update calendar events
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AcademicCalendar $academicCalendar): bool
    {
        // Only admin can delete calendar events
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AcademicCalendar $academicCalendar): bool
    {
        // Only admin can restore calendar events
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AcademicCalendar $academicCalendar): bool
    {
        // Only admin can permanently delete calendar events
        return $user->role === 'admin';
    }
}
