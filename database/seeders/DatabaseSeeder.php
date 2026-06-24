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
        $investor = \App\Models\User::create(['name' => 'Diaspora Investor', 'email' => 'investor@agrotrace.com', 'password' => bcrypt('password'), 'role' => 'investor']);
        $owner = \App\Models\User::create(['name' => 'Cooperative Maize #042', 'email' => 'coop@agrotrace.com', 'password' => bcrypt('password'), 'role' => 'project_owner']);

        // Project
        $project = \App\Models\Project::create([
            'user_id' => $owner->id,
            'title' => 'Maize Cooperative #042',
            'description' => 'Eliminating the Trust Gap in agricultural investment in Malanville, North Benin.',
            'region' => 'Malanville, North Benin',
            'target_amount_fcfa' => 5000000,
            'status' => 'verified',
            'image' => 'https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?auto=format&fit=crop&q=80&w=800'
        ]);

        // Milestones
        \App\Models\Milestone::create(['project_id' => $project->id, 'title' => 'Purchase of hybrid maize seeds', 'amount_fcfa' => 1000000, 'status' => 'validated', 'proof_image' => 'seeds.jpg', 'tx_hash' => '0xabcd1234']);
        \App\Models\Milestone::create(['project_id' => $project->id, 'title' => 'Irrigation system setup', 'amount_fcfa' => 2000000, 'status' => 'funded', 'tx_hash' => '0xefgh5678']);
        \App\Models\Milestone::create(['project_id' => $project->id, 'title' => 'Harvest logistics & storage', 'amount_fcfa' => 2000000, 'status' => 'pending']);

        // Investment
        \App\Models\Investment::create([
            'project_id' => $project->id,
            'user_id' => $investor->id,
            'amount_fcfa' => 50000,
            'amount_sats' => 300000,
            'fee_sats' => 6000,
            'payment_hash' => 'lnbc300k1p...',
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
