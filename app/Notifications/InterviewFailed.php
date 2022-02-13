<?php

namespace App\Notifications;

use App\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InterviewFailed extends Notification implements ShouldQueue
{
    use Queueable;

    private $exam;

    /**
     * Create a new notification instance.
     */
    public function __construct(Exam $exam)
    {
        $this->exam = $exam;
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
        // Si vous avez activé les notifications par courrier électronique
        if ($this->exam->user->email_verified && $this->exam->user->email_enabled) {
            return ['mail', 'database'];
        }
        // Sinon, par base de donnée
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
        if (0 == $this->exam->user->getExamTriesRemaining()) {
            return (new MailMessage())
                ->subject('Votre entretien')
                ->from(config('mail.from.address'), config('mail.from.name'))
                ->error()
                ->greeting('Nous avons une mauvaise nouvelle')
                ->line('Nous avons le regret de vous informer que vous avez échoué à l\'ensemble de vos entretiens.')
                ->line('Il ne vous est donc plus possible de rejoindre notre projet');
        }

        $url = route('setup-rules');

        return (new MailMessage())
            ->subject('Votre entretien')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->error()
            ->greeting('Nous avons une mauvaise nouvelle')
            ->line('Nous sommes au regret de vous informer que vous avez échoué votre entretien.')
            ->line('Cependant, il vous est possible de le repasser ! Nous vous recommandons de revoir les règles:')
            ->action('Voir règlement', $url)
            ->line('Si vous voulez de l\'aide ou si vous avez des questions, n\'hésitez pas à nous les poser.');
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
        return [
            'icon'        => 'sentiment_very_dissatisfied',
            'title'       => 'Votre entretien',
            'message'     => 'Nous avons le regret de vous informer que vous n\'avez pas réussi votre entretien. Cependant, vous pouvez le repasser. Nous vous recommandons de revoir les règles. Si vous avez des doutes, n\'hésitez pas à nous demander.',
            'url'         => route('setup-rules'),
            'button_text' => 'Revoir le règlement',
        ];
    }
}
