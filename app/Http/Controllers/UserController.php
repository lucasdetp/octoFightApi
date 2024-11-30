<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Rapper;

class UserController extends Controller
{
    public function getUser(Request $request)
    {
        return response()->json(Auth::user());
    }

    public function getUserByUsername($username)
    {
        $user = User::where('name', $username)->first();

        if (!$user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        return response()->json($user);
    }

    // public function getRappers($userId)
    // {
    //     $user = User::find($userId);

    //     if (!$user) {
    //         return response()->json(['error' => 'Utilisateur non trouvé'], 404);
    //     }

    //     $rappers = $user->rappers;
    //     return response()->json(['rappers' => $rappers], 200);
    // }


    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8',
        ]);

        $user = Auth::user();

        if (!($user instanceof User)) {
            return response()->json(['message' => 'Utilisateur non trouvé.'], 404);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Le mot de passe actuel est incorrect.'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Mot de passe mis à jour avec succès.'], 200);
    }

    public function getCredits()
    {
        $user = Auth::user();
        return response()->json(['credits' => $user->credits]);
    }

    public function getDeck(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        $maxAttack = 100;
        $maxDefense = 100;

        $deck = $user->rappers->map(function ($rapper) use ($maxAttack, $maxDefense) {
            $attaque = ($rapper->popularity * 1.1) + ($rapper->followers / 1500000);
            $attaque = min($attaque, $maxAttack);

            $defense = ($rapper->followers * 0.00003) + ($rapper->popularity * 0.2);
            $defense = min($defense, $maxDefense);

            if ($rapper->popularity >= 75) {
                $rarity = 'légendaire';
            } elseif ($rapper->popularity >= 65) {
                $rarity = 'épique';
            } elseif ($rapper->popularity >= 55) {
                $rarity = 'rare';
            } else {
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

        return response()->json($deck);
    }
}
