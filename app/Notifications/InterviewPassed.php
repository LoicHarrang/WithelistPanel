<?php

namespace App\Notifications;

use App\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InterviewPassed extends Notification implements ShouldQueue
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
        $url = "http://static.arma3frontiere.fr/dl/a3f_setup.exe";

        return (new MailMessage())
            ->subject('Votre dossier d\'inscription')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->greeting('Bienvenue dans la communautée d\'Arma 3 Frontière !')
            ->line('')
            ->line('Ce mail est destiné à vous expliquer comment rejoindre notre serveur.')
            ->line('')
            ->line('Installation du launcher et des mods:')
            ->line('')
            ->line('Un launcher c’est quoi ?')
            ->line('C’est un logiciel qui sert à télécharger et installer notre modpack et ensuite de lancer votre jeu tout en vérifiant que vous jouez avec les mods à jours.')
            ->line('Vous pouvez également y télécharger la version que nous utilisons du mod “TaskForce Radio”, ce mod permet un meilleur gameplay sonore en jeu.')
            ->line('')
            ->line('Vous pouvez télécharger le launcher via la page d\'accueil du dashboard ou via le boutton dans cet email.')
            ->line('Une fois le launcher téléchargé, il vous suffit de lancer l\'exécutable et de procéder à l’installation, il se peut que votre antivirus bloque l’installation, si vous rencontrez ce problème, désactivez votre antivirus le temps de l’installation, n’oubliez pas de le réactiver après l’installation !')
            ->line('')
            ->line('Après cette étape, ouvrez le launcher, cliquez sur l’onglet “MAJ” et le launcher va se mettre à télécharger nos mods, pendant ce temps là vous pouvez télécharger TaskForce Radio en allant dans les paramètres launcher, une fenêtre apparaîtra lorsque l’installation des mods sera finie, vous pourrez ensuite cliquer sur “Jouer” pour commencer votre aventure !')
            ->line('')
            ->line('Si vous avez la moindre question n’hésitez pas à la poser sur Teamspeak.')
            ->line('')
            ->line('Bon jeu sur Arma 3 Frontière !')
            ->action('Commencer à jouer', $url);
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
            'icon'    => 'check_circle',
            'title'   => 'Bienvenue dans la communautée d\'Arma 3 Frontière !',
            'message' => 'Vous avez terminé avec succès votre inscription, vous avez désormais l\'accès a la totalité de notre infrastructure. \n Vous avez également reçu un mail vous éxpliquant comment nous rejoindre si vous souaithé avoir plus d\'informations',
        ];
    }
}
