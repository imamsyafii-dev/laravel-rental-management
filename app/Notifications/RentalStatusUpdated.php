<?php

namespace App\Notifications;

use App\Models\Rental;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RentalStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Rental $rental,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = match ($this->rental->status) {
            'approved' => 'Pengajuan rental Anda telah disetujui.',
            'rejected' => 'Pengajuan rental Anda ditolak.',
            'completed' => 'Rental Anda telah selesai.',
            default => 'Status rental Anda telah berubah.',
        };

        return (new MailMessage)
            ->subject('Update Rental ' . $this->rental->rental_code)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line($message)
            ->line('Kode rental: ' . $this->rental->rental_code)
            ->line('Status: ' . ucfirst($this->rental->status))
            ->action('Lihat Rental', url('/rentals'))
            ->line('Terima kasih.');
    }
}