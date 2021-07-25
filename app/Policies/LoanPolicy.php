<?php

namespace App\Policies;

use App\Models\Loan\Loan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoanPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any loans.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can view the loan.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Loan\Loan  $loan
     * @return mixed
     */
    public function view(User $user, Loan $loan)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can create loans.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can update the loan.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Loan\Loan  $loan
     * @return mixed
     */
    public function update(User $user, Loan $loan)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can delete the loan.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Loan\Loan  $loan
     * @return mixed
     */
    public function delete(User $user, Loan $loan)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can restore the loan.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Loan\Loan  $loan
     * @return mixed
     */
    public function restore(User $user, Loan $loan)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the loan.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Loan\Loan  $loan
     * @return mixed
     */
    public function forceDelete(User $user, Loan $loan)
    {
        //
    }
}
