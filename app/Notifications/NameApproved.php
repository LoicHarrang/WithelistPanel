<?php

namespace App\Notifications;

use App\Name;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NameApproved extends Notification implements ShouldQueue
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
                ->greeting('Félicitation !')
                ->line('Votre identité a été approuvée par nos services.')
                ->line('Vous pouvez désormais rejoindre le serveur si vous avez déjà réussi votre entretien.')
                ->line('Si ce n\'est pas le cas, présentez-vous sur TeamSpeak au plus vite ! :).');
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
                'icon'    => 'account_circle',
                'title'   => 'Identité accepté',
                'message' => 'Votre identité ('.$this->name->name.') a été acceptée, vous pouvez désormais utiliser cette identité de façon permanente.',
            ];
        }
        if ('changed' == $this->name->type) {
            if (! is_null($this->name->original_name)) {
                return [
                    'icon'    => 'account_circle',
                    'title'   => 'Identité accepté, avec changement',
                    'message' => 'Votre identité ('.$this->name->name.') a été acceptée, avec quelques changements (avant modification "'.$this->name->original_name.'"). Si vous pensez qu\'il s\'agit d\'une erreur, veuillez nous contacter. Vous pouvez désormais utiliser cette identité de façon permanente.',
                ];
            }

            return [
                'icon'    => 'account_circle',
                'title'   => 'Identité accepté',
                'message' => 'Votre identité ('.$this->name->name.') a été acceptée, vous pouvez désormais utiliser cette identité de façon permanente.',
            ];
        }

        if (! is_null($this->name->original_name)) {
            return [
                'icon'    => 'account_circle',
                'title'   => 'Identité accepté, avec changement',
                'message' => 'Votre identité ('.$this->name->name.') a été acceptée, avec quelques changements (avant modification "'.$this->name->original_name.'"). Si vous pensez qu\'il s\'agit d\'une erreur, veuillez nous contacter. Vous pouvez désormais utiliser cette identité de façon permanente.',
            ];
        }

        return [
            'icon'    => 'account_circle',
            'title'   => 'Identité accepté',
            'message' => 'Votre identité ('.$this->name->name.') a été acceptée, vous pouvez désormais utiliser cette identité de façon permanente.',
        ];
    }
}
