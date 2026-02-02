<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admin can view all bookings, clients can view their own, trainers with permission
        return $user->role === 'admin' || $user->role === 'client' ||
               ($user->role === 'trainer' && $user->trainer && $user->trainer->hasAccessTo('bookings'));
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Booking $booking): bool
    {
        // Admin can view all
        if ($user->role === 'admin') {
            return true;
        }

        // Clients can only view their own bookings
        if ($user->role === 'client') {
            return $booking->client->user_id === $user->id;
        }

        // Trainers with permission can view all
        if ($user->role === 'trainer' && $user->trainer && $user->trainer->hasAccessTo('bookings')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admin can create bookings, clients can create their own, trainers with permission
        return $user->role === 'admin' || $user->role === 'client' ||
               ($user->role === 'trainer' && $user->trainer && $user->trainer->hasAccessTo('bookings'));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Booking $booking): bool
    {
        // Admin can update any booking
        if ($user->role === 'admin') {
            return true;
        }

        // Clients can update their own pending bookings
        if ($user->role === 'client') {
            return $booking->client->user_id === $user->id && $booking->status === 'pending';
        }

        // Trainers with permission can update all
        if ($user->role === 'trainer' && $user->trainer && $user->trainer->hasAccessTo('bookings')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Booking $booking): bool
    {
        // Admin can delete, trainers with permission can delete
        return $user->role === 'admin' ||
               ($user->role === 'trainer' && $user->trainer && $user->trainer->hasAccessTo('bookings'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Booking $booking): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Booking $booking): bool
    {
        return false;
    }
}
