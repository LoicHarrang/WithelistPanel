<?php

namespace App\Notifications;

use App\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExamFailed extends Notification implements ShouldQueue
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
        $url = route('setup-rules');

        return (new MailMessage())
                    ->subject('Examen')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->error()
                    ->greeting('Nous avons une mauvaise nouvelle')
                    ->line('Nous avons le regret de vous informer que vous n\'avez pas réussi l\'épreuve écrite.')
                    ->line('Nous vous encourageons à réessayer après avoir lu les règles une ou deux fois de plus.')
                    ->action('Voir règlement', $url)
                    ->line('Ne vous découragez pas, vous pouvez le faire ! Si vous voulez de l\'aide ou si vous avez des questions, n\'hésitez pas à nous les poser.');
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
            'title'       => 'Examen',
            'message'     => 'Nous avons le regret de vous informer que vous n\'avez pas réussi l\'examen. Essayez de nouveau après avoir passé les règles en revue. Si vous avez des questions, n\'hésitez pas à nous les poser, nous serons heureux de vous guider.',
            'url'         => route('setup-rules'),
            'button_text' => 'Voir règlement',
        ];
    }
}
