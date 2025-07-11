<?php

namespace App\Notifications;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyEnrolledUsersAboutNewLessonNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Course $course,
        private string $lesson_slug
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🎉 New Lesson Published: '.$this->course->title)
            ->greeting('Hi '.$notifiable->name)
            ->line('You have a new lesson in '.$this->course->title)
            ->action('Check out Your New Lesson', route('lessons.player', [$this->course->slug, $this->lesson_slug]))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [

            'route' => [
                'name' => 'lessons.player',
                'params' => [$this->course->slug, $this->lesson_slug],
            ],

            'title' => 'New Lesson Added',
            'message' => "You have a new lesson in {$this->course->title}",
        ];
    }
}
