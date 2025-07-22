<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Notification delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the reset password email message.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Your frontend URL (set in .env)
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');

        // Full reset link (frontend will consume it)
        $url = $frontendUrl . '/reset-password?token=' . $this->token . '&email=' . urlencode($notifiable->email);

        return (new MailMessage)
            ->subject('Reset Your Password')
            ->line('You requested a password reset. Click the button below to set a new password.')
            ->action('Reset Password', $url)
            ->line('This link will expire in 60 minutes.')
            ->line('If you did not request a password reset, no action is needed.');
    }

    /**
     * Array representation (optional).
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }
}
