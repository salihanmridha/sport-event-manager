<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ChangeEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        protected $token,
        protected $newEmail,
        protected $nameUser,
        protected $oldEmail)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        try {
            return $this
                ->to($this->newEmail)
                ->subject('PALARO - Change your account’s email address ')
                ->view('emails.verify-email', [
                    'token' => $this->token,
                    'newEmail' => $this->newEmail,
                    'oldEmail' => $this->oldEmail,
                    'nameUser' => $this->nameUser,
                ]);
        } catch (\Exception $exception) {
            Log::error($exception);
        }

    }
}
