<?php

namespace App\Notifications;

use App\Name;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NameRejected extends Notification implements ShouldQueue
{
    use Queueable;

    private $name;

    /**
     * Create a new notification instance.
     */
    public function __construct(Name $name)
    {
        $this->name = $name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        if ($this->name->user->email_verified && $this->name->user->email_enabled) {
            return ['mail', 'database'];
        }
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
                ->subject('Votre identité "'.$this->name->name.'"')
                ->from(config('mail.from.address'), config('mail.from.name'))
                ->greeting('Oops, nous avons une mauvaise nouvelle')
                ->line('Votre identité n\'a pas été approuvée par nos services.')
                ->line('Vous pouvez toujours retenter votre chance ! Pour se faire, faites comme ceci:')
                ->line('Rendez-vous sur notre dashboard, de là, il vous proposera de recréer une identité')
                ->line('Prenez cette fois-ci compte du règlement relatif à la création d\'identité')
                ->line('Une fois cela fait, nos équipes réévalueront votre demande');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        if ('imported' == $this->name->type) {
            return [
                'icon'     => 'account_circle',
                'title'    => 'Identité rejeté',
                'message'  => 'Votre identité ('.$this->name->name.') a été concidérée innaproprié par nos équipes. Un email avec tout les détails vous à été envoyé.'
            ];
        }
        if ('change' == $this->name->type) {
            return [
                'icon'     => 'account_circle',
                'title'    => 'Changement d\'identité rejeté',
                'message'  => 'Votre identité ('.$this->name->name.') a été concidérée innaproprié par nos équipes. Un email avec tout les détails vous à été envoyé.'
            ];
        }

        return [
            'icon'     => 'account_circle',
            'title'    => 'Identité rejeté',
            'message'  => 'Votre identité ('.$this->name->name.') a été concidérée innaproprié par nos équipes. Un email avec tout les détails vous à été envoyé.'
        ];
    }
}
