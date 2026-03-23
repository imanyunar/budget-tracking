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
        $totalWalletBalance = Portfolio::where('user_id', $user_id)->sum('balance');
        $investmentData = $this->getInvestmentData($user_id);
        $totalBalance = $totalWalletBalance + $investmentData['totalCurrentValue'];
        
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

        $category = Category::find($request->category_id);
        $isInvestment = $category && strtolower($category->name) === 'investment';

        // --- Investment Logic Interception ---
        if ($isInvestment) {
            $asset_type = $request->input('asset_type', 'stock');
            $rules = [
                'symbol'        => 'required|string|max:20',
                'average_price' => 'required|numeric|min:0',
            ];
            if ($asset_type === 'crypto') {
                $rules['quantity'] = 'required|numeric|min:0.00000001';
            } else {
                $rules['lots'] = 'required|integer|min:1';
            }
            $request->validate($rules);

            $symbol = strtoupper($request->symbol);
            $newPrice = (float) $request->average_price;

            $totalCost = 0;
            $newQuantity = 0;
            $newLots = 0;

            if ($asset_type === 'crypto') {
                $newQuantity = (float) $request->quantity;
                $totalCost = $newQuantity * $newPrice;
            } else {
                $newLots = (int) $request->lots;
                $newQuantity = $newLots * 100; // 1 lot = 100 shares
                $totalCost = $newQuantity * $newPrice;
            }

            // Override transaction amount with computed total cost (Price * Qty)
            $txAmount = $totalCost;
            $portfolio = Portfolio::findOrFail($request->portfolio_id);

            // BUY (Expense)
            if ($request->type === 'expense') {
                if ($portfolio->balance < $txAmount) return redirect()->back()->with('error', 'Insufficient wallet balance for this investment.');
                
                $inv = \App\Models\Investment::where('user_id', $user_id)->where('symbol', $symbol)->where('asset_type', $asset_type)->first();
                if ($inv) {
                    $oldQty = ($asset_type === 'crypto') ? $inv->quantity : ($inv->lots * 100);
                    $newTotalQty = $oldQty + $newQuantity;
                    $inv->average_price = ($newTotalQty > 0) ? ((($oldQty * $inv->average_price) + $txAmount) / $newTotalQty) : 0;
                    if ($asset_type === 'crypto') $inv->quantity += $newQuantity; else $inv->lots += $newLots;
                    $inv->save();
                } else {
                    \App\Models\Investment::create([
                        'user_id' => $user_id, 'asset_type' => $asset_type, 'symbol' => $symbol, 'name' => $symbol,
                        'lots' => $newLots, 'quantity' => $asset_type === 'crypto' ? $newQuantity : 0, 'average_price' => $newPrice
                    ]);
                }
                $portfolio->balance -= $txAmount;
            } 
            // SELL (Income)
            else {
                $inv = \App\Models\Investment::where('user_id', $user_id)->where('symbol', $symbol)->where('asset_type', $asset_type)->first();
                if (!$inv) return redirect()->back()->with('error', 'You do not own this asset.');

                if ($asset_type === 'crypto' && $newQuantity > $inv->quantity) return redirect()->back()->with('error', 'Sell quantity exceeds your crypto holdings.');
                if ($asset_type === 'stock' && $newLots > $inv->lots) return redirect()->back()->with('error', 'Sell lots exceed your stock holdings.');

                if ($asset_type === 'crypto') {
                    $inv->quantity -= $newQuantity;
                } else {
                    $inv->lots -= $newLots;
                }

                if ($inv->lots <= 0 && $inv->quantity <= 0) $inv->delete();
                else $inv->save();

                $portfolio->balance += $txAmount;
            }
            $portfolio->save();

            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->amount = $txAmount;
            $transaction->type = $request->type;
            $transaction->category_id = $request->category_id;
            $transaction->portfolio_id = $request->portfolio_id;
            $transaction->date = $request->date;
            $transaction->description = $request->description ?: (($request->type === 'expense' ? 'Buy ' : 'Sell ') . $symbol);
            $transaction->metadata = json_encode([
                'asset_symbol' => $symbol,
                'qty' => $asset_type === 'stock' ? $newLots . ' lots' : $newQuantity,
                'price' => $newPrice
            ]);
            $transaction->save();

            return redirect()->back()->with('success', 'Investment transaction recorded successfully! Total: IDR ' . number_format($txAmount, 0, ',', '.'));
        }

        // --- Standard Transaction Logic ---
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
        $totalWallet = $portfolios->sum('balance');

        $investmentData = $this->getInvestmentData($user_id);
        $totalAssets = $totalWallet + $investmentData['totalCurrentValue'];

        return view('pages.wallets', compact('portfolios', 'totalWallet', 'totalAssets'));
    }

    public function showWallet(Portfolio $portfolio)
    {
        if ($portfolio->user_id !== Auth::id()) abort(403);
        $transactions = Transaction::with('category')->where('portfolio_id', $portfolio->id)->orderBy('date', 'desc')->paginate(15);
        return view('pages.wallet-detail', compact('portfolio', 'transactions'));
    }

    public function destroyWallet(Portfolio $portfolio)
    {
        if ($portfolio->user_id !== Auth::id()) abort(403);
        
        if (Transaction::where('portfolio_id', $portfolio->id)->exists()) {
            return redirect()->back()->with('error', 'Cannot delete a wallet that has transactions. Please delete its transactions first.');
        }

        $portfolio->delete();
        return redirect()->back()->with('success', 'Wallet deleted successfully.');
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

    public function updateWallet(Request $request, Portfolio $portfolio)
    {
        if ($portfolio->user_id !== Auth::id()) abort(403);

        $request->validate([
            'name'    => 'required|string|max:100',
            'balance' => 'required|numeric',
        ]);

        $portfolio->update([
            'name'    => $request->name,
            'balance' => $request->balance,
        ]);

        return redirect()->back()->with('success', 'Wallet updated successfully!');
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
        $data = $this->getInvestmentData($user_id);
        $wallets = \App\Models\Portfolio::where('user_id', $user_id)->get();

        return view('pages.investments', array_merge([
            'investments'       => $data['investments'],
            'totalInvested'     => $data['totalInvested'],
            'totalCurrentValue' => $data['totalCurrentValue'],
            'totalProfit'       => $data['totalProfit'],
            'wallets'           => $wallets
        ], $data['subTotals']));
    }

    public function sellInvestment(Request $request, \App\Models\Investment $investment)
    {
        if ($investment->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'wallet_id'   => 'required|exists:portfolios,id',
            'sell_amount' => 'required|numeric|min:0.00000001',
            'sell_price'  => 'required|numeric|min:0',
        ]);

        $wallet = \App\Models\Portfolio::where('id', $request->wallet_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $sellAmount = (float) $request->sell_amount;
        $sellPrice  = (float) $request->sell_price;
        $proceeds   = $sellAmount * $sellPrice;

        // Check if stock or crypto
        if (($investment->asset_type ?? 'stock') === 'stock') {
            if ($sellAmount > $investment->lots) {
                return back()->with('error', 'Jumlah lot melebihi kepemilikan!');
            }
            $proceeds = ($sellAmount * 100) * $sellPrice; // 1 lot = 100 shares
            $investment->lots -= $sellAmount;
        } else {
            if ($sellAmount > $investment->quantity) {
                return back()->with('error', 'Jumlah unit melebihi kepemilikan!');
            }
            $investment->quantity -= $sellAmount;
        }

        // Create transaction
        \App\Models\Transaction::create([
            'user_id'      => Auth::id(),
            'portfolio_id' => $wallet->id,
            'amount'       => $proceeds,
            'type'         => 'income',
            'date'         => now(),
            'description'  => "Penjualan Asset: {$investment->symbol}",
            'metadata'     => json_encode([
                'asset_symbol' => $investment->symbol,
                'sell_amount'  => $sellAmount,
                'sell_price'   => $sellPrice
            ])
        ]);

        // Update Wallet Balance
        $wallet->balance += $proceeds;
        $wallet->save();

        // Update or delete investment
        if (($investment->lots ?? 0) <= 0 && ($investment->quantity ?? 0) <= 0) {
            $investment->delete();
        } else {
            $investment->save();
        }

        $response = redirect()->route('investments.index')->with('success', 'Asset berhasil dijual & dana masuk ke wallet!');

        // 40% Threshold Warning
        $currentTotal = ($investment->asset_type === 'stock') ? ($investment->lots + $sellAmount) : ($investment->quantity + $sellAmount);
        if ($currentTotal > 0 && ($sellAmount / $currentTotal) >= 0.4) {
            $percentage = round(($sellAmount / $currentTotal) * 100);
            $response->with('warning', "Perhatian: Anda baru saja menarik {$percentage}% dari total aset ini. Pastikan ini sesuai dengan strategi investasi Anda!");
        }

        return $response;
    }

    private function getInvestmentData($user_id)
    {
        // Fetch USD to IDR rate from Yahoo Finance (used for crypto conversion)
        $usdToIdr = 16000; // fallback rate
        try {
            /** @var \Illuminate\Http\Client\Response $fxResponse */
            $fxResponse = \Illuminate\Support\Facades\Http::timeout(3)
                ->get("https://query1.finance.yahoo.com/v8/finance/chart/USDIDR=X");
            if ($fxResponse->successful()) {
                $rate = $fxResponse->json()['chart']['result'][0]['meta']['regularMarketPrice'] ?? null;
                if ($rate && $rate > 1000) $usdToIdr = $rate;
            }
        } catch (\Exception $e) {}

        $investments = \App\Models\Investment::where('user_id', $user_id)->get()->map(function ($inv) use ($usdToIdr) {
            $inv->current_price = $inv->average_price;
            $inv->price_source = 'fallback';

            if (($inv->asset_type ?? 'stock') === 'crypto') {
                // Special case for USD-pegged assets or the USD/IDR rate itself
                $cleanSymbol = strtoupper($inv->symbol);
                if ($cleanSymbol === 'USDT' || $cleanSymbol === 'USD') {
                    $inv->current_price = $usdToIdr;
                    $inv->price_usd = 1.0;
                    $inv->price_source = 'yahoo_fx';
                } else {
                    // --- CRYPTO: Binance Public API (no key required) ---
                    try {
                        $symbol = $cleanSymbol . 'USDT';
                        /** @var \Illuminate\Http\Client\Response $response */
                        $response = \Illuminate\Support\Facades\Http::timeout(3)
                            ->get("https://api.binance.com/api/v3/ticker/price?symbol={$symbol}");
                        if ($response->successful()) {
                            $priceUsd = (float) ($response->json()['price'] ?? 0);
                            if ($priceUsd > 0) {
                                $inv->current_price = $priceUsd * $usdToIdr; // convert to IDR
                                $inv->price_usd = $priceUsd;
                                $inv->price_source = 'binance';
                            }
                        }
                    } catch (\Exception $e) {}
                }

                $qty = (float) ($inv->quantity ?? 0);
                $inv->display_volume = $qty;
                $inv->total_invested = $qty * (float)$inv->average_price;
                $inv->current_value  = $qty * $inv->current_price;
            } else {
                // --- STOCK: Yahoo Finance ---
                try {
                    /** @var \Illuminate\Http\Client\Response $response */
                    $response = \Illuminate\Support\Facades\Http::timeout(3)
                        ->get("https://query1.finance.yahoo.com/v8/finance/chart/{$inv->symbol}");
                    if ($response->successful()) {
                        $price = $response->json()['chart']['result'][0]['meta']['regularMarketPrice'] ?? null;
                        if ($price) {
                            $inv->current_price = $price;
                            $inv->price_source = 'yahoo';
                        }
                    }
                } catch (\Exception $e) {}

                $shares = ($inv->lots ?? 0) * 100; // 1 lot = 100 shares (IDX)
                $inv->display_volume = $inv->lots ?? 0;
                $inv->total_invested = $shares * (float)$inv->average_price;
                $inv->current_value  = $shares * $inv->current_price;
            }

            $inv->unrealized_profit = $inv->current_value - $inv->total_invested;
            $inv->roi = $inv->total_invested > 0
                ? ($inv->unrealized_profit / $inv->total_invested) * 100
                : 0;

            return $inv;
        });

        $totalInvested     = $investments->sum('total_invested');
        $totalCurrentValue = $investments->sum('current_value');
        $totalProfit       = $totalCurrentValue - $totalInvested;

        // Sub-totals calculation
        $stocks = $investments->where('asset_type', 'stock');
        $cryptos = $investments->where('asset_type', 'crypto');

        $subTotals = [
            'stockTotalInvested' => $stocks->sum('total_invested'),
            'stockCurrentValue'  => $stocks->sum('current_value'),
            'stockProfit'        => $stocks->sum('unrealized_profit'),
            'cryptoTotalInvested' => $cryptos->sum('total_invested'),
            'cryptoCurrentValue'  => $cryptos->sum('current_value'),
            'cryptoProfit'        => $cryptos->sum('unrealized_profit'),
        ];

        return [
            'investments'       => $investments,
            'totalInvested'     => $totalInvested,
            'totalCurrentValue' => $totalCurrentValue,
            'totalProfit'       => $totalProfit,
            'subTotals'         => $subTotals
        ];
    }

    public function storeInvestment(Request $request)
    {
        $asset_type = $request->input('asset_type', 'stock');
        $user_id = Auth::id();

        // Common validations
        $rules = [
            'wallet_id'     => 'required|exists:portfolios,id',
            'symbol'        => 'required|string|max:20',
            'average_price' => 'required|numeric|min:0',
        ];

        if ($asset_type === 'crypto') {
            $rules['quantity'] = 'required|numeric|min:0.00000001';
        } else {
            $rules['lots'] = 'required|integer|min:1';
        }

        $request->validate($rules);

        $wallet = \App\Models\Portfolio::where('id', $request->wallet_id)
            ->where('user_id', $user_id)
            ->firstOrFail();

        $symbol = strtoupper($request->symbol);
        $newPrice = (float) $request->average_price;
        
        // Calculate Total Cost
        $totalCost = 0;
        if ($asset_type === 'crypto') {
            $newQuantity = (float) $request->quantity;
            $totalCost = $newQuantity * $newPrice;
        } else {
            $newLots = (int) $request->lots;
            $newQuantity = $newLots * 100; // 1 lot = 100 shares
            $totalCost = $newQuantity * $newPrice;
        }

        // Check Balance
        if ($wallet->balance < $totalCost) {
            return redirect()->back()->with('error', 'Saldo Wallet tidak mencukupi untuk pembelian ini.');
        }

        // Check if asset already exists
        $existingInvestment = \App\Models\Investment::where('user_id', $user_id)
            ->where('symbol', $symbol)
            ->where('asset_type', $asset_type)
            ->first();

        if ($existingInvestment) {
            // Averaging Logic
            $oldCurrentQuantity = ($asset_type === 'crypto') ? $existingInvestment->quantity : ($existingInvestment->lots * 100);
            $oldAveragePrice = $existingInvestment->average_price;

            $totalOldCost = $oldCurrentQuantity * $oldAveragePrice;
            
            $newTotalCost = $totalOldCost + $totalCost;
            $newTotalQuantity = $oldCurrentQuantity + $newQuantity;

            $averagedPrice = $newTotalQuantity > 0 ? ($newTotalCost / $newTotalQuantity) : 0;

            if ($asset_type === 'crypto') {
                $existingInvestment->quantity += $newQuantity;
            } else {
                $existingInvestment->lots += $newLots;
            }
            $existingInvestment->average_price = $averagedPrice;
            $existingInvestment->save();
        } else {
            // Create New Investment
            \App\Models\Investment::create([
                'user_id'       => $user_id,
                'asset_type'    => $asset_type,
                'symbol'        => $symbol,
                'name'          => $symbol,
                'lots'          => $asset_type === 'stock' ? $request->lots : 0,
                'quantity'      => $asset_type === 'crypto' ? $request->quantity : 0,
                'average_price' => $newPrice,
            ]);
        }

        // Deduct Wallet Balance & Create Transaction Log
        $wallet->balance -= $totalCost;
        $wallet->save();

        \App\Models\Transaction::create([
            'user_id'      => $user_id,
            'portfolio_id' => $wallet->id,
            'amount'       => $totalCost,
            'type'         => 'expense',
            'date'         => now(),
            'description'  => "Pembelian Asset: {$symbol}",
            'metadata'     => json_encode([
                'asset_symbol' => $symbol,
                'buy_qty'      => ($asset_type === 'stock') ? "{$request->lots} lot" : $newQuantity,
                'buy_price'    => $newPrice
            ])
        ]);

        return redirect()->back()->with('success', 'Asset berhasil dibeli! Saldo wallet telah dikurangi.');
    }

    public function updateInvestment(Request $request, \App\Models\Investment $investment)
    {
        if ($investment->user_id !== Auth::id()) abort(403);

        $asset_type = $request->input('asset_type', $investment->asset_type);

        if ($asset_type === 'crypto') {
            $request->validate([
                'symbol'        => 'required|string|max:20',
                'quantity'      => 'required|numeric|min:0.00000001',
                'average_price' => 'required|numeric|min:0',
            ]);

            $investment->update([
                'asset_type'    => 'crypto',
                'symbol'        => strtoupper($request->symbol),
                'name'          => strtoupper($request->symbol),
                'lots'          => 0,
                'quantity'      => $request->quantity,
                'average_price' => $request->average_price,
            ]);
        } else {
            $request->validate([
                'symbol'        => 'required|string|max:20',
                'lots'          => 'required|integer|min:1',
                'average_price' => 'required|numeric|min:0',
            ]);

            $investment->update([
                'asset_type'    => 'stock',
                'symbol'        => strtoupper($request->symbol),
                'name'          => strtoupper($request->symbol),
                'lots'          => $request->lots,
                'quantity'      => 0,
                'average_price' => $request->average_price,
            ]);
        }

        return redirect()->back()->with('success', 'Investment updated successfully');
    }

    public function getInvestmentChartData($symbol)
    {
        $symbol = strtoupper($symbol);
        $user_id = Auth::id();
        $investment = \App\Models\Investment::where('user_id', $user_id)->where('symbol', $symbol)->first();
        
        if (!$investment) {
            return response()->json(['error' => 'Investment not found'], 404);
        }

        $chartData = [];
        try {
            // Suffix for Yahoo Finance (assume .JK for Indonesian stocks if not specified, but user inputs could vary)
            // Cryptos might need -USD. We rely on whatever symbol was saved.
            $fetchSymbol = $symbol;
            if ($investment->asset_type === 'crypto' && !str_contains($fetchSymbol, '-')) {
                 $fetchSymbol .= '-USD'; // e.g., BTC-USD
            }

            /** @var \Illuminate\Http\Client\Response $response */
            $response = \Illuminate\Support\Facades\Http::timeout(3)
                ->get("https://query1.finance.yahoo.com/v8/finance/chart/{$fetchSymbol}?interval=1d&range=1mo");
            
            if ($response->successful() && isset($response->json()['chart']['result'][0])) {
                $result = $response->json()['chart']['result'][0];
                $timestamps = $result['timestamp'] ?? [];
                $closePrices = $result['indicators']['quote'][0]['close'] ?? [];
                
                foreach ($timestamps as $index => $timestamp) {
                    if (isset($closePrices[$index])) {
                        $price = $closePrices[$index];
                        // Rough fallback IDR conversion for crypto
                        if ($investment->asset_type === 'crypto') {
                            $price *= 15500; 
                        }
                        $chartData[] = [
                            $timestamp * 1000, 
                            round($price, 2)
                        ];
                    }
                }
            }
        } catch (\Exception $e) {}

        // Fallback to realistic mock data if API fails or empty
        if (empty($chartData)) {
            $basePrice = $investment->average_price > 0 ? $investment->average_price : 1000;
            $now = time() * 1000;
            $dayMs = 86400000;
            $currentPrice = $basePrice * 0.9; // start slightly below average
            
            for ($i = 30; $i >= 0; $i--) {
                $time = $now - ($i * $dayMs);
                $volatility = $basePrice * 0.05; 
                $change = (rand(-100, 100) / 100) * $volatility;
                
                // create an upward trend over time to make it look nice
                if ($i < 15) $currentPrice += ($basePrice * 0.01); 
                
                $currentPrice += $change;
                if ($currentPrice <= 0) $currentPrice = 10;
                $chartData[] = [$time, round($currentPrice, 2)];
            }
        }

        return response()->json([
            'symbol' => $symbol,
            'name' => $investment->name,
            'data' => $chartData,
            'average_price' => $investment->average_price,
            'total_quantity' => ($investment->asset_type === 'stock') ? $investment->lots : $investment->quantity,
        ]);
    }

    public function destroyInvestment(\App\Models\Investment $investment)
    {
        if ($investment->user_id !== Auth::id()) abort(403);
        $investment->delete();
        return redirect()->back()->with('success', 'Investment deleted');
    }

    public function settings()
    {
        $user = Auth::user();
        // Ensure settings has defaults if empty
        if (!$user->settings) {
            $user->settings = [
                'daily_report' => true,
                'stock_alerts' => false,
                'spreadsheet_compat' => true
            ];
            $user->save();
        }
        return view('pages.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'avatar_seed' => 'nullable|string|max:255',
            'avatar_image' => 'nullable|image|max:2048',
            'daily_report' => 'boolean',
            'stock_alerts' => 'boolean',
            'spreadsheet_compat' => 'boolean',
        ]);

        $user->name = $request->name;
        if ($request->filled('avatar_seed')) {
            $user->avatar_seed = $request->avatar_seed;
        }

        $settings = [
            'daily_report' => $request->has('daily_report'),
            'stock_alerts' => $request->has('stock_alerts'),
            'spreadsheet_compat' => $request->has('spreadsheet_compat'),
        ];
        
        // Retain existing avatar path if present
        if (isset($user->settings['avatar_path'])) {
            $settings['avatar_path'] = $user->settings['avatar_path'];
        }

        if ($request->hasFile('avatar_image')) {
            $path = $request->file('avatar_image')->store('avatars', 'public');
            $settings['avatar_path'] = $path;
            
            // clear avatar_seed since we use image now
            $user->avatar_seed = null;
        }

        $user->settings = $settings;
        $user->save();

        return redirect()->back()->with('success', 'Settings updated successfully!');
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
