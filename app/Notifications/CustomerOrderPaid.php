<?php

namespace App\Notifications;

use Barryvdh\DomPDF\Facade as PDF;
use DoubleThreeDigital\SimpleCommerce\Contracts\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerOrderPaid extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            'mail',
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $pdf = PDF::loadView('vendor.simple-commerce.receipt', $this->order->toAugmentedArray());
        return (new MailMessage)
            ->subject('Potrditev prijave na 5. Novomeški 1/2 Maraton')
            ->markdown('vendor.notifications.customer_order_paid', [
                'order' => $this->order,
            ])
            ->attachData(
                $pdf->output(),
                'racun.pdf',
                ['mime' => 'application/pdf']
            );
    }
}
