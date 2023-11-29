<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $codeConfirmation = null;
    public function __construct($code)
    {
        //
        $this->codeConfirmation = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('andribis13@gmail.com')
                    ->view('emailku')
                    ->with(
                    [
                        'nama' => 'Test Email',
                        'website' => 'example.com',
                        'code' => $this->codeConfirmation
                    ]
                );;
    }
}
