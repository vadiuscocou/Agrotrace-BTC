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
            'region' => 'Malanville, Nord Bénin',
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
            'region' => 'Ouidah, Sud Bénin',
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

        // Demo Project 3 (En étude - under_review)
        $project3 = \App\Models\Project::create([
            'user_id' => $owner->id,
            'title' => 'Coopérative de Cacao "Fèves Magiques"',
            'description' => 'Modernisation des équipements de séchage de cacao pour améliorer la qualité et le prix de vente.',
            'region' => 'Divo, Côte d\'Ivoire',
            'target_amount_fcfa' => 8000000,
            'status' => 'under_review',
            'latitude' => 5.8374,
            'longitude' => -5.3572,
            'image' => 'https://images.unsplash.com/photo-1582212952409-bc015f8aebcc?auto=format&fit=crop&q=80&w=800'
        ]);

        \App\Models\Milestone::create(['project_id' => $project3->id, 'title' => 'Achat des séchoirs solaires', 'amount_fcfa' => 4000000, 'status' => 'pending']);
        \App\Models\Milestone::create(['project_id' => $project3->id, 'title' => 'Formation des agriculteurs', 'amount_fcfa' => 2000000, 'status' => 'pending']);
        \App\Models\Milestone::create(['project_id' => $project3->id, 'title' => 'Logistique et transport', 'amount_fcfa' => 2000000, 'status' => 'pending']);

        // Demo Project 4 (Terminé - completed)
        $project4 = \App\Models\Project::create([
            'user_id' => $owner->id,
            'title' => 'Plantation de Tomates Bio',
            'description' => 'Culture de tomates sous serre avec système d\'irrigation intelligent.',
            'region' => 'Niayes, Sénégal',
            'target_amount_fcfa' => 3500000,
            'status' => 'completed',
            'latitude' => 14.8833,
            'longitude' => -17.0333,
            'image' => 'https://images.unsplash.com/photo-1592841200221-a6898f307baa?auto=format&fit=crop&q=80&w=800'
        ]);

        \App\Models\Milestone::create(['project_id' => $project4->id, 'title' => 'Installation des serres', 'amount_fcfa' => 2000000, 'status' => 'validated', 'proof_image' => 'serre.jpg', 'tx_hash' => '0xbeef5678']);
        \App\Models\Milestone::create(['project_id' => $project4->id, 'title' => 'Achat des semences et engrais bio', 'amount_fcfa' => 1500000, 'status' => 'validated', 'proof_image' => 'engrais.jpg', 'tx_hash' => '0xcafe9012']);

        \App\Models\Investment::create([
            'project_id' => $project4->id,
            'user_id' => $investor->id,
            'amount_fcfa' => 3500000,
            'amount_sats' => 21000000,
            'fee_sats' => 420000,
            'payment_hash' => 'lnbc210k1p...',
            'status' => 'paid'
        ]);

        // Demo Project 5 (Validé, en attente de fonds - awaiting_funding)
        $project5 = \App\Models\Project::create([
            'user_id' => $owner->id,
            'title' => 'Riziculture de la Vallée',
            'description' => 'Aménagement de parcelles pour la culture de riz irrigué à haut rendement.',
            'region' => 'Vallée du Fleuve, Sénégal',
            'target_amount_fcfa' => 6000000,
            'status' => 'awaiting_funding',
            'latitude' => 16.3268,
            'longitude' => -14.9388,
            'image' => 'https://images.unsplash.com/photo-1586771107445-d3af9e152003?auto=format&fit=crop&q=80&w=800'
        ]);

        \App\Models\Milestone::create(['project_id' => $project5->id, 'title' => 'Aménagement du terrain', 'amount_fcfa' => 3000000, 'status' => 'pending']);
        \App\Models\Milestone::create(['project_id' => $project5->id, 'title' => 'Achat de semences de riz', 'amount_fcfa' => 1500000, 'status' => 'pending']);
        \App\Models\Milestone::create(['project_id' => $project5->id, 'title' => 'Moissonneuse en location', 'amount_fcfa' => 1500000, 'status' => 'pending']);

        // Demo Project 6 (Soumis - submitted)
        $project6 = \App\Models\Project::create([
            'user_id' => $owner->id,
            'title' => 'Plantation de Manguiers Améliorés',
            'description' => 'Création d\'un verger de manguiers greffés pour l\'exportation de mangues fraîches.',
            'region' => 'Sikasso, Mali',
            'target_amount_fcfa' => 4500000,
            'status' => 'submitted',
            'latitude' => 11.3174,
            'longitude' => -5.6666,
            'image' => 'https://images.unsplash.com/photo-1605807646983-377bc5a76493?auto=format&fit=crop&q=80&w=800'
        ]);
        
        \App\Models\Milestone::create(['project_id' => $project6->id, 'title' => 'Achat des plants de manguiers', 'amount_fcfa' => 2000000, 'status' => 'pending']);
        \App\Models\Milestone::create(['project_id' => $project6->id, 'title' => 'Forage pour irrigation', 'amount_fcfa' => 2500000, 'status' => 'pending']);

        // Demo Project 7 (En cours - in_progress)
        $project7 = \App\Models\Project::create([
            'user_id' => $owner->id,
            'title' => 'Apiculture et Production de Miel',
            'description' => 'Installation de 500 ruches modernes pour la production de miel pur de forêt.',
            'region' => 'Forêt de Lama, Bénin',
            'target_amount_fcfa' => 1800000,
            'status' => 'in_progress',
            'latitude' => 6.9500,
            'longitude' => 2.1667,
            'image' => 'https://images.unsplash.com/photo-1587049352847-ecbfba39f997?auto=format&fit=crop&q=80&w=800'
        ]);
        
        \App\Models\Milestone::create(['project_id' => $project7->id, 'title' => 'Fabrication des ruches', 'amount_fcfa' => 800000, 'status' => 'validated', 'proof_image' => 'ruches.jpg', 'tx_hash' => '0xbeef1111']);
        \App\Models\Milestone::create(['project_id' => $project7->id, 'title' => 'Matériel de récolte et tenues', 'amount_fcfa' => 500000, 'status' => 'submitted', 'proof_image' => 'combinaisons.jpg']);
        \App\Models\Milestone::create(['project_id' => $project7->id, 'title' => 'Centrifugeuse à miel', 'amount_fcfa' => 500000, 'status' => 'pending']);

        \App\Models\Investment::create([
            'project_id' => $project7->id,
            'user_id' => $investor->id,
            'amount_fcfa' => 1800000,
            'amount_sats' => 10800000,
            'fee_sats' => 216000,
            'payment_hash' => 'lnbc108k1p...',
            'status' => 'paid'
        ]);
        
        // Subscription
        \App\Models\Subscription::create([
            'user_id' => $owner->id,
            'status' => 'active',
            'expires_at' => now()->addMonth()
        ]);
    }
}
