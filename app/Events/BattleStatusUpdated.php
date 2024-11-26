<?php

namespace App\Events;

use App\Models\Battle;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class BattleStatusUpdated
{
    use Dispatchable, SerializesModels;

    public $battle;

    public function __construct(Battle $battle)
    {
        $this->battle = $battle;
    }

    public function broadcastOn()
    {
        return new Channel('battle.' . $this->battle->id); 
    }
}
