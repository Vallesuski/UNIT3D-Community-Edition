<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewPost extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * NewPost Constructor.
     */
    public function __construct(public string $type, public User $user, public Post $post)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        if ($this->type == 'subscription') {
            return [
                'title' => $this->user->username.' Has Posted In A Subscribed Topic',
                'body'  => $this->user->username.' has left a new post in Subscribed Topic '.$this->post->topic->name,
                'url'   => \sprintf('/forums/topics/%s/posts/%s', $this->post->topic->id, $this->post->id),
            ];
        }

        if ($this->type == 'staff') {
            return [
                'title' => $this->user->username.' Has Posted In A Staff Forum Topic',
                'body'  => $this->user->username.' has left a new post in Staff Topic '.$this->post->topic->name,
                'url'   => \sprintf('%s/posts/%s', route('topics.show', ['id' => $this->post->topic->id]), $this->post->id),
            ];
        }

        return [
            'title' => $this->user->username.' Has Posted In A Topic You Started',
            'body'  => $this->user->username.' has left a new post in Your Topic '.$this->post->topic->name,
            'url'   => \sprintf('/forums/topics/%s/posts/%s', $this->post->topic->id, $this->post->id),
        ];
    }
}
