<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Searchinvoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class SearchinvoicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_searchinvoice');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Searchinvoice  $searchinvoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Searchinvoice $searchinvoice)
    {
        return $user->can('view_searchinvoice');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create_searchinvoice');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Searchinvoice  $searchinvoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Searchinvoice $searchinvoice)
    {
        return $user->can('update_searchinvoice');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Searchinvoice  $searchinvoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Searchinvoice $searchinvoice)
    {
        return $user->can('delete_searchinvoice');
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteAny(User $user)
    {
        return $user->can('delete_any_searchinvoice');
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Searchinvoice  $searchinvoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Searchinvoice $searchinvoice)
    {
        return $user->can('force_delete_searchinvoice');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDeleteAny(User $user)
    {
        return $user->can('force_delete_any_searchinvoice');
    }

    /**
     * Determine whether the user can restore.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Searchinvoice  $searchinvoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Searchinvoice $searchinvoice)
    {
        return $user->can('restore_searchinvoice');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restoreAny(User $user)
    {
        return $user->can('restore_any_searchinvoice');
    }

    /**
     * Determine whether the user can replicate.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Searchinvoice  $searchinvoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function replicate(User $user, Searchinvoice $searchinvoice)
    {
        return $user->can('replicate_searchinvoice');
    }

    /**
     * Determine whether the user can reorder.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function reorder(User $user)
    {
        return $user->can('reorder_searchinvoice');
    }

}
