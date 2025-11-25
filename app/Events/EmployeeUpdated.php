<?php
namespace App\Events;

use App\Models\Employee;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmployeeUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $employee;

    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('employees'),
        ];
    }

    public function broadcastAs()
    {
        return 'employee.updated';
    }
}