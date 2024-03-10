<?php

namespace App\Policies;

use App\Models\ChirpLike;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChirpLikePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ChirpLike $chirpLike): bool
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ChirpLike $chirplike): bool
    {
        //
        return $chirplike->user()->is($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ChirpLike $chirplike): bool
    {
        return $this->update($user, $chirplike);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ChirpLike $chirpLike): bool
    {
        //
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ChirpLike $chirplike): bool
    {
        return false;
    }
}
