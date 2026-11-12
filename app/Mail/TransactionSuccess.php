<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;
    public $user;
    public $service;
    public $amount;
    public $newBalance;
    public $isAdmin;
    public $token;

    public function __construct($transaction, $user, $service, $amount, $newBalance, $isAdmin, $token = "")
    {
        $this->transaction = $transaction;
        $this->user = $user;
        $this->service = $service;
        $this->amount = $amount;
        $this->newBalance = $newBalance;
        $this->isAdmin = $isAdmin;
        $this->token = $token;
    }

    public function build()
    {
        return $this->subject('Transaction Successful')
                    ->markdown('emails.transaction.success');
    }
}
