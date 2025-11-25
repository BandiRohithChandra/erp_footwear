<?php
namespace App\Events;

use App\Models\Payroll;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PayrollUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payroll;

    public function __construct(Payroll $payroll)
    {
        $this->payroll = $payroll;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('payrolls'),
        ];
    }

    public function broadcastAs()
    {
        return 'payroll.updated';
    }
}