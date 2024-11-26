<?php

namespace App\Observers;

use App\Models\Battle;
use App\Models\User;
use App\Models\Rapper;
use Illuminate\Support\Facades\Log;

class BattleObserver
{
    public function updated(Battle $battle)
    {
        Log::info('Battle updated: ', $battle->toArray());

        if ($battle->user1_rapper_id && $battle->user2_rapper_id && $battle->status === 'accepted') {
            Log::info('Both rappers chosen. Processing the battle logic.');

            $user1Rapper = Rapper::find($battle->user1_rapper_id);
            $user2Rapper = Rapper::find($battle->user2_rapper_id);

            Log::info('User 1 rapper: ', $user1Rapper ? $user1Rapper->toArray() : []);
            Log::info('User 2 rapper: ', $user2Rapper ? $user2Rapper->toArray() : []);

            if (!$user1Rapper || !$user2Rapper) {
                Log::error('One or both rappers not found.');
                return;
            }

            if ($user1Rapper->popularity > $user2Rapper->popularity) {
                $battle->winner_id = $battle->user1_id;
                $this->rewardWinner($battle->user1_id);
            } elseif ($user2Rapper->popularity > $user1Rapper->popularity) {
                $battle->winner_id = $battle->user2_id;
                $this->rewardWinner($battle->user2_id);
            } else {
                $battle->winner_id = null; 
            }

            $battle->status = 'completed';
            $battle->save();
        }
    }

    private function rewardWinner($userId)
    {
        $user = User::find($userId);

        if ($user) {
            $user->credit += 100;
            $user->save();
            Log::info('Winner rewarded: User ' . $userId . ' now has ' . $user->credit . ' credits');
        }
    }
}
