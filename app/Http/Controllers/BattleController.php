<?php

namespace App\Http\Controllers;

use App\Models\Battle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BattleController extends Controller
{
    // Inviter un ami à un combat
    public function invite(Request $request)
    {
        $user = Auth::user(); // Utilisateur actuel (l'inviteur)
        $invitedUserId = $request->input('invited_user_id'); // ID de l'utilisateur invité

        // Vérifier si l'utilisateur invité existe
        $invitedUser = User::find($invitedUserId);

        if (!$invitedUser) {
            return response()->json(['error' => 'Utilisateur invité non trouvé'], 404);
        }

        // Créer un combat entre l'utilisateur actuel et l'utilisateur invité
        $battle = Battle::create([
            'user1_id' => $user->id, // L'utilisateur actuel (inviteur)
            'user2_id' => $invitedUserId, // L'utilisateur invité
            'status' => 'pending', // Statut du combat (en attente)
        ]);

        // Retourner la réponse sans notification en temps réel
        return response()->json(['battle' => $battle], 201);
    }

    public function getActiveBattleForUser($userId)
    {
        $battle = Battle::where(function ($query) use ($userId) {
                $query->where('user1_id', $userId)
                    ->orWhere('user2_id', $userId);
            })
            ->whereIn('status', ['accepted']) 
            ->first();

        if ($battle) {
            $selectedRapper = null;

            if ($battle->user1_id == $userId) {
                $selectedRapper = $battle->user1_rapper_id; 
            } elseif ($battle->user2_id == $userId) {
                $selectedRapper = $battle->user2_rapper_id; 
            }

            if ($selectedRapper) {
                return response()->json([
                    'battle' => null,
                ]);
            }

            return response()->json([
                'battle' => [
                    'id' => $battle->id,
                    'status' => $battle->status,
                ],
            ]);
        }

        return response()->json([
            'battle' => null,
        ]);
    }



    // Accepter une invitation
public function acceptBattle($battleId, Request $request)
{
    $battle = Battle::findOrFail($battleId);

    if ($battle->status !== 'pending') {
        return response()->json(['error' => 'L\'invitation n\'est plus valide'], 400);
    }

    $battle->status = 'accepted';
    $battle->user2_rapper_id = null; // Reset si nécessaire
    $battle->save();

    // Émettre un événement pour notifier les deux utilisateurs
    broadcast(new \App\Events\BattleAccepted($battle));

    return response()->json(['battle' => $battle]);
}

    

public function chooseRapper($battleId, Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'Non authentifié.'], 401);
    }

    $battle = Battle::findOrFail($battleId);

    $request->validate([
        'rapper_id' => 'required|exists:rappers,id',
    ]);

    if ($battle->user1_id == $user->id) {
        $battle->user1_rapper_id = $request->rapper_id;
    } elseif ($battle->user2_id == $user->id) {
        $battle->user2_rapper_id = $request->rapper_id;
    } else {
        return response()->json(['error' => 'Non autorisé'], 403);
    }

    $battle->save();

    return response()->json(['message' => 'Rappeur choisi avec succès']);
}


    // Refuser une invitation
    public function declineBattle($battleId)
    {
        $battle = Battle::findOrFail($battleId);
        
        if ($battle->status !== 'pending') {
            return response()->json(['error' => 'L\'invitation n\'est plus valide'], 400);
        }

        $battle->status = 'declined';
        $battle->save();


        return response()->json(['battle' => $battle]);
    }
    // Résoudre un combat et déterminer le gagnant
    public function resolveBattle($battleId)
    {
        $battle = Battle::findOrFail($battleId);
        
        $winnerId = $this->determineWinner($battle->user1_rapper_id, $battle->user2_rapper_id);
        $battle->winner_id = $winnerId;
        $battle->status = 'completed';
        $battle->save();


        return response()->json(['battle' => $battle, 'winner' => $winnerId]);
    }

    // Méthode pour déterminer le gagnant
    private function determineWinner($rapper1Id, $rapper2Id)
    {
        $rapper1 = Rapper::find($rapper1Id);
        $rapper2 = Rapper::find($rapper2Id);

        return ($rapper1->power > $rapper2->power) ? $rapper1->user_id : $rapper2->user_id;
    }

    public function checkPendingInvitations($userId)
    {
        // Rechercher une invitation en attente pour l'utilisateur (user2)
        $invitations = Battle::where('user2_id', $userId)
                            ->where('status', 'pending')
                            ->get();
                            
        return response()->json([
            'invitations' => $invitations
        ]);
    }
}
