<?php

namespace App\Listeners\Loan;

use App\Events\Loan\CreatingLoanEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreatingLoanListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CreatingLoanEvent  $event
     * @return void
     */
    public function handle(CreatingLoanEvent $event)
    {
        $loan = $event->loan;

        $loan->setStatusLoanToInLoan();
    }
}
