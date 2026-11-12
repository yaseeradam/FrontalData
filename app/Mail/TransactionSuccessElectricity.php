<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionSuccessElectricity extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;
    public $user;
    public $service;
    public $amount;
    public $token;
    public $isAdmin;

    public function __construct($transaction, $user, $service, $amount, $token, $isAdmin)
    {
        $this->transaction = $transaction;
        $this->user = $user;
        $this->service = $service;
        $this->amount = $amount;
        $this->token = $token;
        $this->isAdmin = $isAdmin;
    }

    public function build()
    {
        return $this->subject('Transaction Successful')
                    ->markdown('emails.transaction.successE');
    }
}
