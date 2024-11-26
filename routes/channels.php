<?php

use App\Models\Battle;
use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('battle.{battleId}', function ($user, $battleId) {
    $battle = \App\Models\Battle::find($battleId);
    return $battle && ($battle->user1_id === $user->id || $battle->user2_id === $user->id);
});
