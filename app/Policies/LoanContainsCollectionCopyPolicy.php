<?php

namespace App\Policies;

use App\Models\LoanContainsCollectionCopy;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoanContainsCollectionCopyPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any loan contains collection copies.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the loan contains collection copy.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LoanContainsCollectionCopy  $loanContainsCollectionCopy
     * @return mixed
     */
    public function view(User $user, LoanContainsCollectionCopy $loanContainsCollectionCopy)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can create loan contains collection copies.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can update the loan contains collection copy.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LoanContainsCollectionCopy  $loanContainsCollectionCopy
     * @return mixed
     */
    public function update(User $user, LoanContainsCollectionCopy $loanContainsCollectionCopy)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can delete the loan contains collection copy.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LoanContainsCollectionCopy  $loanContainsCollectionCopy
     * @return mixed
     */
    public function delete(User $user, LoanContainsCollectionCopy $loanContainsCollectionCopy)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can restore the loan contains collection copy.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LoanContainsCollectionCopy  $loanContainsCollectionCopy
     * @return mixed
     */
    public function restore(User $user, LoanContainsCollectionCopy $loanContainsCollectionCopy)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the loan contains collection copy.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LoanContainsCollectionCopy  $loanContainsCollectionCopy
     * @return mixed
     */
    public function forceDelete(User $user, LoanContainsCollectionCopy $loanContainsCollectionCopy)
    {
        //
    }
}
