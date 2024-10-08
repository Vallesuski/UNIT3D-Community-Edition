<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Warning;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserManualWarningExpire extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $user, public Warning $warning)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $profileUrl = href_profile($this->user);

        return (new MailMessage())
            ->greeting('Manual Warning Expired!')
            ->line('Your Warning has expired!')
            ->action('View Profile!', $profileUrl)
            ->line('Thank you for using 🚀'.config('other.title'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Manual Warning Expired',
            'body'  => 'You were warned for '.$this->warning->reason.'. That warning has now expired.',
            'url'   => \sprintf('/users/%s', $this->user->username),
        ];
    }
}
