<?php

namespace App\Listeners\Loan;

use App\Events\Loan\CreatedLoanEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreatedLoanListener
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
     * @param  CreatedLoanEvent  $event
     * @return void
     */
    public function handle(CreatedLoanEvent $event)
    {
        $loan = $event->loan;

        $loan->setStatusLoanToInLoan();
    }
}
