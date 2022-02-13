<?php

namespace App\Notifications;

use App\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExamPassed extends Notification implements ShouldQueue
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
        $url = route('setup-forum');
        $date = $this->exam->expires_at->setTimezone($this->exam->user->timezone)->format('d/m/Y (h:i)');

        return (new MailMessage())
                ->subject('Examen')
                ->from(config('mail.from.address'), config('mail.from.name'))
                ->greeting('Félicitation !')
                ->line('Vous avez validé votre examen.')
                ->line('Vous avez presque terminée, rendez vous à l\'étape suivante.')
                ->action('Prochaine étape', $url)
                ->line('Nous vous rappelons que vous avez jusqu\'au '.$date.' pour terminer votre inscription.');
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
        $date = $this->exam->expires_at->setTimezone($this->exam->user->timezone)->format('d/m/Y (h:i)');

        return [
            'icon'    => 'done',
            'title'   => 'Examen',
            'message' => 'Félicitation, vous avez terminé votre examen, passer maintenant à l\'étape suivante ! Vous avez jusqu\'au '.$date.' pour terminer votre inscription.',
        ];
    }
}
