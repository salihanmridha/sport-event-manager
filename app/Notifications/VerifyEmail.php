<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class VerifyEmail extends Notification
{

    public function __construct(
        protected $token,
        protected $newEmail,
        protected $nameUser,
        protected $oldEmail
    )
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        try {
            return (new MailMessage)
                ->subject('PALARO - Change your account’s email address ')
                ->view('emails.verify-email', [
                    'token' => $this->token,
                    'newEmail' => $this->newEmail,
                    'oldEmail' => $this->oldEmail,
                    'nameUser' => $this->nameUser,
                ]);
        } catch (\Exception $exception){
            Log::error($exception);
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
