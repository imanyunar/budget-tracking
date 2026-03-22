<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Portfolio;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        $user = Auth::user();

        // Totals
        $totalBalance = Portfolio::where('user_id', $user_id)->sum('balance');
        
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $monthlyIncome = Transaction::where('user_id', $user_id)
            ->where('type', 'income')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        $monthlyExpense = Transaction::where('user_id', $user_id)
            ->where('type', 'expense')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        // Last Month Data for Trends
        $lastMonthIncome = Transaction::where('user_id', $user_id)
            ->where('type', 'income')
            ->whereMonth('date', Carbon::now()->subMonth()->month)
            ->whereYear('date', Carbon::now()->subMonth()->year)
            ->sum('amount');

        $lastMonthExpense = Transaction::where('user_id', $user_id)
            ->where('type', 'expense')
            ->whereMonth('date', Carbon::now()->subMonth()->month)
            ->whereYear('date', Carbon::now()->subMonth()->year)
            ->sum('amount');

        $incomeTrend = $lastMonthIncome > 0 ? (($monthlyIncome - $lastMonthIncome) / $lastMonthIncome) * 100 : 0;
        $expenseTrend = $lastMonthExpense > 0 ? (($monthlyExpense - $lastMonthExpense) / $lastMonthExpense) * 100 : 0;

        // Weekly Report Logic
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        $startOfLastWeek = now()->subWeek()->startOfWeek();
        $endOfLastWeek = now()->subWeek()->endOfWeek();

        $spentThisWeek = Transaction::where('user_id', $user_id)->where('type', 'expense')->whereBetween('date', [$startOfWeek, $endOfWeek])->sum('amount');
        $spentLastWeek = Transaction::where('user_id', $user_id)->where('type', 'expense')->whereBetween('date', [$startOfLastWeek, $endOfLastWeek])->sum('amount');
        $weeklyTrend = $spentLastWeek > 0 ? (($spentThisWeek - $spentLastWeek) / $spentLastWeek) * 100 : 0;

        $topCategoryThisWeek = Transaction::with('category')
            ->where('user_id', $user_id)
            ->where('type', 'expense')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->selectRaw('category_id, sum(amount) as total')
            ->groupBy('category_id')
            ->orderBy('total', 'desc')
            ->first();

        // Cashflow Chart Data (last 6 months)
        $cashflowData = Transaction::select(
                DB::raw("EXTRACT(MONTH FROM date) as month"),
                DB::raw('SUM(CASE WHEN type = \'income\' THEN amount ELSE 0 END) as income'),
                DB::raw('SUM(CASE WHEN type = \'expense\' THEN amount ELSE 0 END) as expense')
            )
            ->where('user_id', $user_id)
            ->where('date', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Category Breakdown (Pie)
        $categoryBreakdown = Transaction::select('categories.name', 'categories.color', DB::raw('SUM(amount) as total'))
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', $user_id)
            ->where('transactions.type', 'expense')
            ->whereMonth('transactions.date', $currentMonth)
            ->groupBy('categories.name', 'categories.color')
            ->get();

        // Portfolios (Asset Allocation)
        $portfolios = Portfolio::where('user_id', $user_id)->get()->map(function ($p) {
            $p->roi = rand(10, 25) / 10; // Mock ROI data
            return $p;
        });

        // Recent Transactions
        $recentTransactions = Transaction::with(['category', 'portfolio'])
            ->where('user_id', $user_id)
            ->orderBy('date', 'desc')
            ->limit(8)
            ->get();

        // Lists for the New Transaction Form
        $categories = Category::all();
        $allPortfolios = Portfolio::where('user_id', $user_id)->get();

        // Notification Engine: Combining Budget Alerts & Anomalies
        $budgetAlerts = [];
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // 1. Large Transaction Detection (> 1,000,000 IDR)
        $largeTx = Transaction::where('user_id', $user_id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->where('amount', '>', 1000000)
            ->orderBy('amount', 'desc')
            ->first();
        
        if ($largeTx) {
            $budgetAlerts[] = [
                'type' => 'info',
                'title' => 'Large Activity Detected',
                'message' => "An unusual transaction for IDR " . number_format($largeTx->amount, 0, ',', '.') . " was recorded on {$largeTx->date->format('M d')}.",
                'icon' => 'activity'
            ];
        }

        // 2. Budget Health Check
        $allBudgets = Budget::with('category')->where('user_id', $user_id)->get();
        foreach($allBudgets as $budget) {
            $spent = Transaction::where('user_id', $user_id)
                ->where('category_id', $budget->category_id)
                ->where('type', 'expense')
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->sum('amount');
            
            $perc = $budget->amount > 0 ? ($spent / $budget->amount) * 100 : 0;
            $percValue = round($perc);
            if ($perc >= 100) {
                $budgetAlerts[] = [
                    'type' => 'danger',
                    'title' => 'Critical: Budget Exceeded',
                    'message' => "Category '{$budget->category->name}' has exceeded its limit ($percValue%).",
                    'icon' => 'zap'
                ];
            } elseif ($perc >= 80) {
                $budgetAlerts[] = [
                    'type' => 'warning',
                    'title' => 'Warning: Budget Alert',
                    'message' => "Category '{$budget->category->name}' is at $percValue% of its budget.",
                    'icon' => 'alert-triangle'
                ];
            }
        }

        return view('dashboard', compact(
            'totalBalance', 'monthlyIncome', 'monthlyExpense', 'incomeTrend', 'expenseTrend',
            'cashflowData', 'categoryBreakdown', 'portfolios', 'recentTransactions', 'categories', 'allPortfolios', 'budgetAlerts',
            'spentThisWeek', 'weeklyTrend', 'topCategoryThisWeek'
        ));
    }

    public function transactions(Request $request)
    {
        $user_id = Auth::id();
        
        $query = Transaction::with(['category', 'portfolio'])
            ->where('user_id', $user_id);

        if ($request->has('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        $transactions = $query->orderBy('date', 'desc')->paginate(15);
        $categories = Category::all();
        $portfolios = Portfolio::where('user_id', $user_id)->get();

        return view('pages.transactions', compact('transactions', 'categories', 'portfolios'));
    }

    public function storeTransaction(Request $request)
    {
        $user_id = Auth::id();
        
        $request->validate([
            'amount' => 'required|numeric',
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'portfolio_id' => 'required|exists:portfolios,id',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        $transaction = new Transaction();
        $transaction->user_id = $user_id;
        $transaction->amount = $request->amount;
        $transaction->type = $request->type;
        $transaction->category_id = $request->category_id;
        $transaction->portfolio_id = $request->portfolio_id;
        $transaction->date = $request->date;
        $transaction->description = $request->description;
        $transaction->save();

        // Update portfolio balance
        $portfolio = Portfolio::find($request->portfolio_id);
        if ($request->type === 'income') {
            $portfolio->balance += $request->amount;
        } else {
            $portfolio->balance -= $request->amount;
        }
        $portfolio->save();

        return redirect()->back()->with('success', 'Transaction recorded successfully!');
    }

    public function exportTransactions()
    {
        $user_id = Auth::id();
        $transactions = Transaction::with(['category', 'portfolio'])
            ->where('user_id', $user_id)
            ->orderBy('date', 'desc')
            ->get();

        $fileName = 'transactions_' . now()->format('Y-m-d') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Date', 'Description', 'Type', 'Category', 'Wallet', 'Amount'];

        $callback = function() use($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $tx) {
                fputcsv($file, [
                    $tx->date->format('Y-m-d'),
                    $tx->description,
                    ucfirst($tx->type),
                    $tx->category->name ?? 'N/A',
                    $tx->portfolio->name ?? 'N/A',
                    $tx->amount
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroyTransaction(Transaction $transaction)
    {
        $user_id = Auth::id();
        if ($transaction->user_id !== $user_id) return abort(403);

        // Revert portfolio balance
        $portfolio = $transaction->portfolio;
        if ($transaction->type === 'income') {
            $portfolio->balance -= $transaction->amount;
        } else {
            $portfolio->balance += $transaction->amount;
        }
        $portfolio->save();

        $transaction->delete();
        return redirect()->back()->with('success', 'Transaction deleted.');
    }

    public function wallets()
    {
        $user_id = Auth::id();
        $portfolios = Portfolio::where('user_id', $user_id)->get();
        return view('pages.wallets', compact('portfolios'));
    }

    public function storeWallet(Request $request)
    {
        $user_id = Auth::id();
        $request->validate(['name' => 'required|string', 'balance' => 'required|numeric']);

        Portfolio::create([
            'user_id' => $user_id,
            'name' => $request->name,
            'balance' => $request->balance,
            'currency' => 'IDR',
            'icon' => 'wallet',
            'color' => '#3b82f6'
        ]);

        return redirect()->back()->with('success', 'Wallet created!');
    }

    public function budgets()
    {
        $user_id = Auth::id();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $budgets = Budget::with('category')->where('user_id', $user_id)->get()->map(function($budget) use ($user_id, $currentMonth, $currentYear) {
            $spent = Transaction::where('user_id', $user_id)
                ->where('category_id', $budget->category_id)
                ->where('type', 'expense')
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->sum('amount');
            
            $budget->spent = $spent;
            $budget->percentage = $budget->amount > 0 ? min(($spent / $budget->amount) * 100, 100) : 0;
            return $budget;
        });

        $categories = Category::where('type', 'expense')->get();
        return view('pages.budgets', compact('budgets', 'categories'));
    }

    public function storeBudget(Request $request)
    {
        $user_id = Auth::id();
        $request->validate(['category_id' => 'required', 'amount' => 'required|numeric']);

        Budget::updateOrCreate(
            ['user_id' => $user_id, 'category_id' => $request->category_id],
            ['amount' => $request->amount, 'period' => 'monthly', 'start_date' => Carbon::now()->startOfMonth()]
        );

        return redirect()->back()->with('success', 'Budget updated!');
    }

    public function investments()
    {
        $user_id = Auth::id();
        $portfolios = Portfolio::where('user_id', $user_id)->get()->map(function ($p) {
            $p->roi = rand(100, 250) / 100; // Mock ROI 1.0 - 2.5
            return $p;
        });
        return view('pages.investments', compact('portfolios'));
    }

    public function settings()
    {
        return view('pages.settings');
    }

    public function clearAllData()
    {
        $user_id = Auth::id();
        Transaction::where('user_id', $user_id)->delete();
        Budget::where('user_id', $user_id)->delete();
        Portfolio::where('user_id', $user_id)->update(['balance' => 0]);
        
        return redirect()->route('dashboard')->with('success', 'All financial records cleared.');
    }
}
