<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PayrollDisbursedNotification extends Notification
{
    use Queueable;

    protected $payroll;

    public function __construct($payroll)
    {
        $this->payroll = $payroll;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => __('Your salary for :date has been disbursed. Amount: :amount', [
                'date' => $this->payroll->payment_date->format('F Y'),
                'amount' => \App\Helpers\FormatMoney::format($this->payroll->total_amount, $this->payroll->region),
            ]),
            'url' => route('employee-portal.index'),
        ];
    }
}