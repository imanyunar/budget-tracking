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
        $user = User::factory()->create([
            'name' => 'Arkansas',
            'email' => 'arkansas@wallet.com',
            'password' => bcrypt('AstanaArkansas123.'),
        ]);

        // Basic Categories
        $categories = [
            ['name' => 'Food & Drinks', 'type' => 'expense', 'icon' => 'utensils', 'color' => '#FF5733'],
            ['name' => 'Shopping', 'type' => 'expense', 'icon' => 'shopping-bag', 'color' => '#C70039'],
            ['name' => 'Transport', 'type' => 'expense', 'icon' => 'car', 'color' => '#900C3F'],
            ['name' => 'Health', 'type' => 'expense', 'icon' => 'heart-pulse', 'color' => '#581845'],
            ['name' => 'Salary', 'type' => 'income', 'icon' => 'wallet', 'color' => '#2ECC71'],
            ['name' => 'Housing', 'type' => 'expense', 'icon' => 'home', 'color' => '#3498DB'],
            ['name' => 'Entertainment', 'type' => 'expense', 'icon' => 'gamepad', 'color' => '#9B59B6'],
            ['name' => 'Investment', 'type' => 'income', 'icon' => 'trending-up', 'color' => '#F1C40F'],
        ];

        foreach ($categories as $cat) {
            \App\Models\Category::create(array_merge($cat, ['user_id' => $user->id]));
        }

        // 1 Default Portfolio
        \App\Models\Portfolio::create([
            'user_id' => $user->id,
            'name' => 'Main Wallet',
            'balance' => 0,
            'currency' => 'IDR',
            'icon' => 'wallet',
            'color' => '#16A085'
        ]);
    }
}
