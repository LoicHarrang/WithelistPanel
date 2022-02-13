<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MessageNotification extends Notification
{
    use Queueable;

    private $user;
    private $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Si vous avez activé les notifications par courrier électronique
        if ($this->user->email_verified && $this->user->email_enabled) {
            return ['mail', 'database'];
        }
        // Sinon, par base de donnée
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Vous avez reçu un message de l\'équipe')
                    ->line('L\'équipe d\'Arma 3 Frontière vous a envoyé un message:')
                    ->line($this->message)
                    ->action('Connection au Panel', url('/'));
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
            'icon'    => 'message',
            'title'   => 'Message de l\'équipe d\'Arma 3 Frontière:',
            'message' => $this->message,
        ];
    }
}
