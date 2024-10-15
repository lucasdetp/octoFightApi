<?php

namespace App\Http\Controllers;

use App\Models\Rapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RapperController extends Controller
{
    public function getRappers()
    {
        $maxAttack = 100;
        $maxDefense = 100;

        $rappers = Rapper::all()->map(function($rapper) use ($maxAttack, $maxDefense) {
            $attaque = ($rapper->popularity * 1.1) + ($rapper->followers / 1500000); 
            $attaque = min($attaque, $maxAttack);

            $defense = ($rapper->followers * 0.00003) + ($rapper->popularity * 0.2);
            $defense = min($defense, $maxDefense);

            if ($rapper->popularity >= 75) {
                $rarity = 'légendaire';
            }elseif ($rapper->popularity >= 65 && $rapper->popularity <= 74) {
                $rarity = 'épique';
            }elseif ($rapper->popularity >= 55 && $rapper->popularity <= 64) {
                $rarity = 'rare';
            }else {
                $rarity = 'commun';
            }

            return [
                'id' => $rapper->id,
                'name' => $rapper->name,
                'image_url' => $rapper->image_url,
                'popularity' => $rapper->popularity,
                'attaque' => round($attaque, 2),   
                'defense' => round($defense, 2),   
                'rarity' => $rarity,
            ];
        });

        return response()->json($rappers);
    }

    public function getRapper($id)
    {
        $maxAttack = 100;
        $maxDefense = 100;

        $rapper = Rapper::find($id);

        if (!$rapper) {
            return response()->json(['error' => 'Rappeur non trouvé'], 404);
        }

        $attaque = ($rapper->popularity * 1.1) + ($rapper->followers / 1500000); 
        $attaque = min($attaque, $maxAttack);

        $defense = ($rapper->followers * 0.00003) + ($rapper->popularity * 0.2);
        $defense = min($defense, $maxDefense);

        return response()->json([
            'id' => $rapper->id,
            'name' => $rapper->name,
            'image_url' => $rapper->image_url,
            'popularity' => $rapper->popularity,
            'followers' => $rapper->followers,
            'attaque' => round($attaque, 2),
            'defense' => round($defense, 2)
        ]);
    }

    public function buyRapper(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        $rapperId = $request->input('rapper_id');

        $rapper = Rapper::find($rapperId);

        if (!$rapper) {
            return response()->json(['error' => 'Rapper not found'], 404);
        }

        $price = $this->getRapperPrice($rapper);

        if ($user->credit && $user->credit < $price) {
            return response()->json(['error' => 'Not enough credits'], 400);
        }

        if ($user->rappers()->where('rapper_id', $rapperId)->exists()) {
            return response()->json(['error' => 'You already own this rapper'], 400);
        }

        $user->credit -= $price;
        $user->save();

        $user->rappers()->attach($rapperId);

        return response()->json([
            'message' => 'Rapper purchased successfully!',
            'rapper' => $rapper,
            'remaining_credit' => $user->credit,
        ]);
    }

    private function getRapperPrice(Rapper $rapper)
    {
        $basePrice = 100;

        $followerFactor = $rapper->followers / 1000000;

        if ($rapper->popularity >= 75) {
            $popularityFactor = $rapper->popularity * 5;
            $price = $basePrice + $popularityFactor + $followerFactor;
            return max($price, 600);  
        } elseif ($rapper->popularity >= 65 && $rapper->popularity <= 74) {

            $popularityFactor = $rapper->popularity * 4.5; 
            $price = $basePrice + $popularityFactor + $followerFactor;
            return max($price, 400);  
        } elseif ($rapper->popularity >= 55 && $rapper->popularity <= 64) {
            $adjustmentFactor = 1;
            if ($rapper->followers < 500000) {
                $adjustmentFactor = 0.6;  
            } elseif ($rapper->followers >= 500000 && $rapper->followers <= 1000000) {
                $adjustmentFactor = 0.8;  
            }

            $popularityFactor = $rapper->popularity * 3.5;
            $price = ($basePrice + $popularityFactor + $followerFactor) * $adjustmentFactor;
            return min($price, 300); 
        } else {
            $popularityFactor = $rapper->popularity * 2.5; 
            $price = $basePrice + $popularityFactor + $followerFactor;
            return max(50, min($price, 150)); 
        }
    }

}
