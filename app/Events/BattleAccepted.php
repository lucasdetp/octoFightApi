<?php

namespace App\Events;

use App\Models\Battle;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\BroadcastEvent;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BattleAccepted extends BroadcastEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $battle;

    public function __construct(Battle $battle)
    {
        $this->battle = $battle;
    }

    public function broadcastOn()
    {
        return new Channel('battle.' . $this->battle->id);
    }

    public function broadcastAs()
    {
        return 'battle.accepted';
    }
}
