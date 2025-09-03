<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class ExportReadyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $fileName;
    protected $downloadUrl;
    protected $exportType;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $fileName, string $downloadUrl, string $exportType = 'Mentoring Visits')
    {
        $this->fileName = $fileName;
        $this->downloadUrl = $downloadUrl;
        $this->exportType = $exportType;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Export is Ready')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your ' . $this->exportType . ' export has been completed successfully.')
            ->line('File: ' . $this->fileName)
            ->action('Download Export', $this->downloadUrl)
            ->line('This download link will expire in 7 days.')
            ->line('Thank you for using TaRL Assessment System!');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'export_ready',
            'export_type' => $this->exportType,
            'file_name' => $this->fileName,
            'download_url' => $this->downloadUrl,
            'expires_at' => now()->addDays(7)->toDateTimeString(),
            'message' => 'Your ' . $this->exportType . ' export is ready for download.'
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'export_type' => $this->exportType,
            'file_name' => $this->fileName,
            'download_url' => $this->downloadUrl,
        ];
    }
}