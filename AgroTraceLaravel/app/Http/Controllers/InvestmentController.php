<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestmentController extends Controller
{
    /**
     * Affiche le contrat d'un investissement spécifique.
     *
     * @param Investment $investment (Route Model Binding)
     * @return \Illuminate\View\View
     */
    public function contract(Investment $investment)
    {
        // 1. Sécurité : Vérifier que l'utilisateur connecté est bien le propriétaire de l'investissement
        // On utilise Auth::id() pour plus de clarté
        if ($investment->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé : Cet investissement ne vous appartient pas.');
        }

        // 2. Charger le projet lié à l'investissement
        // Note : Cela suppose que tu as défini la relation project() dans ton modèle Investment
        $project = $investment->project;

        // 3. Retourner la vue en passant les DEUX variables : l'investissement ET le projet
        return view('investor.contract', compact('investment', 'project'));
    }
}
