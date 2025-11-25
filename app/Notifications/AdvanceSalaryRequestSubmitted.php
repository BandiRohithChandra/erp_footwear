<?php

namespace App\Notifications;

use App\Models\SalaryAdvanceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdvanceSalaryRequestSubmitted extends Notification
{
    use Queueable;

    protected $salaryAdvanceRequest;

    public function __construct(SalaryAdvanceRequest $salaryAdvanceRequest)
    {
        $this->salaryAdvanceRequest = $salaryAdvanceRequest;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "A new salary advance request from {$this->salaryAdvanceRequest->employee->user->name} needs your approval.",
            'url' => route('manager-portal.index'),
        ];
    }
}