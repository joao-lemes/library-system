<?php

namespace Modules\Loan\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Modules\Book\Models\Book;
use Modules\User\Models\User;

class LoanNotification extends Mailable
{
    use Queueable, SerializesModels;
    
    private User $user;
    private Book $book;

    public function __construct(User $user, Book $book)
    {
        $this->user = $user;
        $this->book = $book;
    }

    public function build(): self
    {
        return $this->markdown('loan::emails.notification')
            ->subject(trans('email.loan.checked_out'))
            ->with([
                'userName' => $this->user->name,
                'bookTitle' => $this->book->title,
            ]);
    }

    public function sendMail(): void
    {
        Mail::to(users: $this->user->email)
            ->send(mailable: $this);
    }
}
