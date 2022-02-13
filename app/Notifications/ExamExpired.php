<?php

namespace App\Notifications;

use App\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExamExpired extends Notification implements ShouldQueue
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
        // Sinon, par base de données.
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
        $url = route('setup-exam');

        return (new MailMessage())
            ->subject('Votre examen a expiré')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->error()
            ->greeting('Cela fais un moment que l\'on ne vous a pas vu.')
            ->line('Nous avons le regret de vous informer que votre examen a expiré.')
            ->line('Vous devez repasser l\'épreuve écrite, même si nous pensons que ce ne sera pas un problème.')
            ->action('Repeter l\'examen', $url)
            ->line('Ne vous découragez pas, si vous avez réussi une fois, vous pouvez réussire une deuxième. Vérifiez les règles au cas où elles auraient changé.');
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
            'icon'        => 'alarm',
            'title'       => 'Votre examen a expiré',
            'message'     => 'Votre examen étant expiré, vous devez repasser l\'épreuve écrite. Ne vous découragez pas, si vous avez réussi une fois, vous pouvez réussire une deuxième.',
            'url'         => route('setup-exam'),
            'button_text' => 'Refaire l\'examen',
        ];
    }
}
