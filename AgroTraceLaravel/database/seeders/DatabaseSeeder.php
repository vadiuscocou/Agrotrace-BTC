<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Users
        $admin = \App\Models\User::create(['name' => 'Admin Agro', 'email' => 'admin@agrotrace.com', 'password' => bcrypt('password'), 'role' => 'admin']);
        $investor = \App\Models\User::create(['name' => 'Investisseur', 'email' => 'investor@agrotrace.com', 'password' => bcrypt('password'), 'role' => 'investor']);
        $owner = \App\Models\User::create(['name' => 'Fédération des Coopératives', 'email' => 'coop@agrotrace.com', 'password' => bcrypt('password'), 'role' => 'project_owner']);

        // Project 1 (En cours - in_progress)
        $project = \App\Models\Project::create([
            'user_id' => $owner->id,
            'title' => 'Coopérative de Maïs #042',
            'description' => 'Éliminer le déficit de confiance dans l\'investissement agricole à Malanville, Nord Bénin.',
            'region' => 'Alibori',
            'target_amount_fcfa' => 5000000,
            'status' => 'in_progress',
            'latitude' => 11.8667,
            'longitude' => 3.3833,
            'image' => 'https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?auto=format&fit=crop&q=80&w=800'
        ]);

        // Milestones
        \App\Models\Milestone::create(['project_id' => $project->id, 'title' => 'Achat de semences de maïs hybride', 'amount_fcfa' => 1000000, 'status' => 'validated', 'proof_image' => 'seeds.jpg', 'tx_hash' => '0xabcd1234']);
        \App\Models\Milestone::create(['project_id' => $project->id, 'title' => 'Mise en place du système d\'irrigation', 'amount_fcfa' => 2000000, 'status' => 'submitted', 'tx_hash' => '0xefgh5678']);
        \App\Models\Milestone::create(['project_id' => $project->id, 'title' => 'Logistique de récolte et stockage', 'amount_fcfa' => 2000000, 'status' => 'pending']);

        // Investment (Fully funded)
        \App\Models\Investment::create([
            'project_id' => $project->id,
            'user_id' => $investor->id,
            'amount_fcfa' => 5000000,
            'amount_sats' => 30000000,
            'fee_sats' => 600000,
            'payment_hash' => 'lnbc300k1p...',
            'status' => 'paid'
        ]);

        // Demo Project 2 (Financé - funded)
        $project2 = \App\Models\Project::create([
            'user_id' => $owner->id,
            'title' => 'Ferme Avicole "Les Poulets d\'Or"',
            'description' => 'Extension d\'une ferme avicole pour répondre à la demande locale en œufs et en viande blanche.',
            'region' => 'Atlantique',
            'target_amount_fcfa' => 2500000,
            'status' => 'funded',
            'latitude' => 6.3573,
            'longitude' => 2.0862,
            'image' => 'https://images.unsplash.com/photo-1548550023-2bf3c49b338c?auto=format&fit=crop&q=80&w=800'
        ]);

        \App\Models\Milestone::create(['project_id' => $project2->id, 'title' => 'Achat des poussins et aliments', 'amount_fcfa' => 1000000, 'status' => 'pending']);
        \App\Models\Milestone::create(['project_id' => $project2->id, 'title' => 'Construction de nouveaux poulaillers', 'amount_fcfa' => 1500000, 'status' => 'pending']);

        \App\Models\Investment::create([
            'project_id' => $project2->id,
            'user_id' => $investor->id,
            'amount_fcfa' => 2500000,
            'amount_sats' => 15000000,
            'fee_sats' => 300000,
            'payment_hash' => 'lnbc150k1p...',
            'status' => 'paid'
        ]);

        // Demo Project 3 (En attente de fonds - awaiting_funding)
        $project3 = \App\Models\Project::create([
            'user_id' => $owner->id,
            'title' => 'Riziculture de la Vallée',
            'description' => 'Aménagement de parcelles pour la culture de riz irrigué à haut rendement.',
            'region' => 'Ouémé',
            'target_amount_fcfa' => 6000000,
            'status' => 'awaiting_funding',
            'latitude' => 6.4973,
            'longitude' => 2.6051,
            'image' => 'https://images.unsplash.com/photo-1586771107445-d3af9e152003?auto=format&fit=crop&q=80&w=800'
        ]);

        \App\Models\Milestone::create(['project_id' => $project3->id, 'title' => 'Aménagement du terrain', 'amount_fcfa' => 3000000, 'status' => 'pending']);
        \App\Models\Milestone::create(['project_id' => $project3->id, 'title' => 'Achat de semences de riz', 'amount_fcfa' => 1500000, 'status' => 'pending']);

        \App\Models\Investment::create([
            'project_id' => $project3->id,
            'user_id' => $investor->id,
            'amount_fcfa' => 1500000,
            'amount_sats' => 9000000,
            'fee_sats' => 180000,
            'payment_hash' => 'lnbc90k1p...',
            'status' => 'paid'
        ]);

        // Demo Project 4 (En attente de fonds - awaiting_funding)
        $project4 = \App\Models\Project::create([
            'user_id' => $owner->id,
            'title' => 'Plantation de Manguiers',
            'description' => 'Création d\'un verger de manguiers greffés pour l\'exportation.',
            'region' => 'Zou',
            'target_amount_fcfa' => 4500000,
            'status' => 'awaiting_funding',
            'latitude' => 7.1828,
            'longitude' => 1.9912,
            'image' => 'https://images.unsplash.com/photo-1605807646983-377bc5a76493?auto=format&fit=crop&q=80&w=800'
        ]);
        
        \App\Models\Milestone::create(['project_id' => $project4->id, 'title' => 'Achat des plants de manguiers', 'amount_fcfa' => 2000000, 'status' => 'pending']);
        
        // Unpaid investment example
        \App\Models\Investment::create([
            'project_id' => $project4->id,
            'user_id' => $investor->id,
            'amount_fcfa' => 500000,
            'amount_sats' => 3000000,
            'fee_sats' => 60000,
            'payment_hash' => 'lnbc30k1p...',
            'status' => 'pending'
        ]);

        // Subscription
        \App\Models\Subscription::create([
            'user_id' => $owner->id,
            'status' => 'active',
            'expires_at' => now()->addMonth()
        ]);
    }
}
