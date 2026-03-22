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
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => bcrypt('password'),
        ]);

        // Categories
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

        // Portfolios
        $portfolios = [
            ['name' => 'Savings Account', 'balance' => 25000000, 'currency' => 'IDR', 'icon' => 'bank', 'color' => '#2980B9'],
            ['name' => 'Wallet', 'balance' => 1500000, 'currency' => 'IDR', 'icon' => 'wallet', 'color' => '#16A085'],
            ['name' => 'Stock Portfolio', 'balance' => 50000000, 'currency' => 'IDR', 'icon' => 'bar-chart', 'color' => '#8E44AD'],
        ];

        foreach ($portfolios as $port) {
            \App\Models\Portfolio::create(array_merge($port, ['user_id' => $user->id]));
        }

        // Transactions (last 6 months)
        $expenseCats = \App\Models\Category::where('type', 'expense')->get();
        $salaryCat = \App\Models\Category::where('name', 'Salary')->first();
        $savingsPort = \App\Models\Portfolio::where('name', 'Savings Account')->first();

        for ($i = 0; $i < 6; $i++) {
            $month = \Carbon\Carbon::now()->subMonths($i);
            
            // Monthly Salary
            \App\Models\Transaction::create([
                'user_id' => $user->id,
                'category_id' => $salaryCat->id,
                'portfolio_id' => $savingsPort->id,
                'amount' => 15000000,
                'type' => 'income',
                'description' => 'Monthly Salary',
                'date' => $month->startOfMonth()->addDays(24),
            ]);

            // Random Expenses
            foreach ($expenseCats as $cat) {
                \App\Models\Transaction::create([
                    'user_id' => $user->id,
                    'category_id' => $cat->id,
                    'portfolio_id' => $savingsPort->id,
                    'amount' => rand(200000, 1500000),
                    'type' => 'expense',
                    'description' => 'Monthly ' . $cat->name,
                    'date' => $month->startOfMonth()->addDays(rand(1, 28)),
                ]);
            }
        }

        // Budgets for Demo
        \App\Models\Budget::create([
            'user_id' => $user->id,
            'category_id' => \App\Models\Category::where('name', 'Food & Drinks')->first()->id,
            'amount' => 5000000,
            'period' => 'monthly',
        ]);
        \App\Models\Budget::create([
            'user_id' => $user->id,
            'category_id' => \App\Models\Category::where('name', 'Transport')->first()->id,
            'amount' => 2000000,
            'period' => 'monthly',
        ]);
        \App\Models\Budget::create([
            'user_id' => $user->id,
            'category_id' => \App\Models\Category::where('name', 'Health')->first()->id,
            'amount' => 1000000,
            'period' => 'monthly',
        ]);
    }
}
