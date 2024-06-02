<?php

namespace Modules\Loan\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Loan\Emails\LoanNotification;
use Modules\Loan\Models\Loan;

class SendLoanNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    private Loan $loan;

    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    public function handle(): void
    {
        $user = $this->loan->user;
        $book = $this->loan->book;
        
        (new LoanNotification($user, $book))->sendMail();
    }
}
