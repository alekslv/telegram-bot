<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\Telegram\TelegramChannel;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TelegrammBot extends Notification
{
    use Queueable;

    protected $name;
    public function __construct($name)
    {
       $this->name=$name;
    }

    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
//            ->to($notifiable->telegram_user_id)
            ->content($this->name);
            // (Optional) Blade template for the content.
            // ->view('notification', ['url' => $url])
            // (Optional) Inline Buttons
//            ->button('View Invoice', $url)
//            ->button('Download Invoice', $url)
            // (Optional) Inline Button with callback. You can handle callback in your bot instance
//            ->buttonWithCallback('Confirm', 'confirm_invoice ' . $this->invoice->id);
    }



}
