<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PayrollApprovalNotification extends Notification
{
    use Queueable;

    protected $payroll;
    protected $type;

    public function __construct($payroll, $type)
    {
        $this->payroll = $payroll;
        $this->type = $type; // 'manager' or 'finance'
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $message = $this->type === 'manager'
            ? __('Payroll for :employee requires your approval.', ['employee' => $this->payroll->employee->name])
            : __('Payroll for :employee has been approved by the manager and requires finance approval.', ['employee' => $this->payroll->employee->name]);

        return [
            'message' => $message,
            'url' => route('payrolls.index', ['status' => $this->type === 'manager' ? 'pending' : 'manager_approved']),
        ];
    }
}