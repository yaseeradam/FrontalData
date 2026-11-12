<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionFailure extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;
    public $user;
    public $service;
    public $amount;
    public $response_data;
    public $isAdmin;

    public function __construct($transaction, $user, $service, $amount, $response_data, $isAdmin = false)
    {
        $this->transaction = $transaction;
        $this->user = $user;
        $this->service = $service;
        $this->amount = $amount;
        $this->response_data = $response_data;
        $this->isAdmin = $isAdmin;
    }

    public function build()
    {
        return $this->subject('Transaction Failed')
                    ->markdown('emails.transaction.failure');
    }
}
