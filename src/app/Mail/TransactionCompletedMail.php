<?php

namespace App\Mail;

use App\Models\SoldItem;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public SoldItem $soldItem;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SoldItem $soldItem)
    {
        $this->soldItem = $soldItem;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('【' . config('app.name') . '】取引が完了しました')
            ->view('emails.transaction_completed');
    }
}