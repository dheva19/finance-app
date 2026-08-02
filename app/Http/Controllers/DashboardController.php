<?php

namespace App\Http\Controllers;

use App\Models\Pocket;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $range = $request->query('range', 'this_month');
        $startDateParam = $request->query('start_date');
        $endDateParam = $request->query('end_date');

        $now = Carbon::now();
        $startDate = null;
        $endDate = null;

        switch ($range) {
            case 'today':
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                $rangeLabel = 'Hari Ini';
                break;
            case 'this_week':
                $startDate = $now->copy()->startOfWeek();
                $endDate = $now->copy()->endOfWeek();
                $rangeLabel = 'Minggu Ini';
                break;
            case 'this_month':
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                $rangeLabel = 'Bulan Ini';
                break;
            case 'this_year':
                $startDate = $now->copy()->startOfYear();
                $endDate = $now->copy()->endOfYear();
                $rangeLabel = 'Tahun Ini';
                break;
            case 'custom':
                if ($startDateParam) {
                    $startDate = Carbon::parse($startDateParam)->startOfDay();
                }
                if ($endDateParam) {
                    $endDate = Carbon::parse($endDateParam)->endOfDay();
                }
                if ($startDate && $endDate) {
                    $rangeLabel = $startDate->format('d/m/Y').' - '.$endDate->format('d/m/Y');
                } elseif ($startDate) {
                    $rangeLabel = 'Dari '.$startDate->format('d/m/Y');
                } elseif ($endDate) {
                    $rangeLabel = 'Sampai '.$endDate->format('d/m/Y');
                } else {
                    $rangeLabel = 'Custom';
                }
                break;
            case 'all':
            default:
                $rangeLabel = 'Semua Waktu';
                break;
        }

        $pocketIdParam = $request->query('pocket_id');

        $pockets = Pocket::query()->where('user_id', $userId)->get();

        if ($pocketIdParam && $selectedPocket = $pockets->firstWhere('id', $pocketIdParam)) {
            $totalAmount = $selectedPocket->amount;
            $selectedPocketName = $selectedPocket->name;
        } else {
            $totalAmount = $pockets->sum('amount');
            $selectedPocketName = 'Semua Kantong';
        }

        $transactionQuery = Transaction::query()->where('user_id', $userId);

        if ($pocketIdParam) {
            $transactionQuery->where(function ($q) use ($pocketIdParam) {
                $q->where('from_pocket_id', $pocketIdParam)
                    ->orWhere('to_pocket_id', $pocketIdParam);
            });
        }

        if ($startDate) {
            $transactionQuery->where('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $transactionQuery->where('created_at', '<=', $endDate);
        }
        $transactions = $transactionQuery->with(['category', 'fromPocket', 'toPocket'])->get();

        if ($pocketIdParam) {
            $totalIncome = $transactions->where('type', 'income')->where('to_pocket_id', $pocketIdParam)->sum('amount');
            $totalExpense = $transactions->where('type', 'expense')->where('from_pocket_id', $pocketIdParam)->sum('amount');
            $totalTransfer = $transactions->where('type', 'transfer')->sum('amount');
        } else {
            $totalIncome = $transactions->where('type', 'income')->sum('amount');
            $totalExpense = $transactions->where('type', 'expense')->sum('amount');
            $totalTransfer = $transactions->where('type', 'transfer')->sum('amount');
        }

        $countCard = [
            'total' => $totalAmount,
            'transfer' => $totalTransfer,
            'income' => $totalIncome,
            'expense' => $totalExpense,
            'rangeLabel' => $rangeLabel,
            'selectedPocketName' => $selectedPocketName,
        ];

        $incomeChartData = [];
        $expenseChartData = [];
        $transferChartData = [];
        $transactionChartLabel = [];

        $groupedTransactions = $transactions
            ->groupBy(function ($item) {
                return $item->created_at->format('d/m/Y');
            });

        foreach ($groupedTransactions as $date => $items) {
            $transactionChartLabel[] = $date;
            if ($pocketIdParam) {
                $incomeChartData[] = $items->where('type', 'income')->where('to_pocket_id', $pocketIdParam)->sum('amount');
                $expenseChartData[] = $items->where('type', 'expense')->where('from_pocket_id', $pocketIdParam)->sum('amount');
                $transferChartData[] = $items->where('type', 'transfer')->sum('amount');
            } else {
                $incomeChartData[] = $items->where('type', 'income')->sum('amount');
                $expenseChartData[] = $items->where('type', 'expense')->sum('amount');
                $transferChartData[] = $items->where('type', 'transfer')->sum('amount');
            }
        }

        $transactionChart = [
            'income' => $incomeChartData,
            'expense' => $expenseChartData,
            'transfer' => $transferChartData,
            'label' => $transactionChartLabel,
        ];

        $doughnutChart = [
            'labels' => ['Income', 'Expense', 'Transfer'],
            'data' => [(float) $totalIncome, (float) $totalExpense, (float) $totalTransfer],
        ];

        $pieChart = [
            'labels' => $pockets->pluck('name')->values()->toArray(),
            'data' => $pockets->pluck('amount')->map(fn ($val) => (float) $val)->values()->toArray(),
        ];

        $allTxQuery = Transaction::query()->where('user_id', $userId);
        if ($startDate) {
            $allTxQuery->where('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $allTxQuery->where('created_at', '<=', $endDate);
        }
        $allTransactions = $allTxQuery->get();

        $pocketMetrics = [];
        foreach ($pockets as $pocket) {
            $pIncome = 0;
            $pExpense = 0;
            $pTransfer = 0;

            foreach ($allTransactions as $t) {
                if ($t->type === 'income' && $t->to_pocket_id == $pocket->id) {
                    $pIncome += $t->amount;
                } elseif ($t->type === 'expense' && $t->from_pocket_id == $pocket->id) {
                    $pExpense += $t->amount;
                } elseif ($t->type === 'transfer') {
                    if ($t->from_pocket_id == $pocket->id) {
                        $pTransfer += $t->amount;
                    }
                    if ($t->to_pocket_id == $pocket->id) {
                        $pTransfer += $t->amount;
                    }
                }
            }

            $totalTxValue = $pIncome + $pExpense + $pTransfer;
            if ($totalTxValue > 0) {
                $pocketMetrics[] = [
                    'name' => $pocket->name,
                    'total_tx' => $totalTxValue,
                    'income' => $pIncome,
                    'expense' => $pExpense,
                    'transfer' => $pTransfer,
                ];
            }
        }

        usort($pocketMetrics, function ($a, $b) {
            return $b['total_tx'] <=> $a['total_tx'];
        });

        $topPockets = array_slice($pocketMetrics, 0, 5);

        $barChart = [
            'labels' => array_column($topPockets, 'name'),
            'income' => array_column($topPockets, 'income'),
            'expense' => array_column($topPockets, 'expense'),
            'transfer' => array_column($topPockets, 'transfer'),
        ];


        $categoryTotals = $transactions
            ->whereNotNull('category_id')
            ->groupBy('category_id')
            ->map(function ($items) {
                $categoryName = $items->first()->category->name ?? 'Tanpa Kategori';

                return [
                    'name' => $categoryName,
                    'total' => $items->sum('amount'),
                ];
            })
            ->sortByDesc('total')
            ->values();

        $categoryBarChart = [
            'labels' => $categoryTotals->pluck('name')->toArray(),
            'data' => $categoryTotals->pluck('total')->map(fn ($v) => (float) $v)->toArray(),
        ];

        $filter = [
            'range' => $range,
            'pocket_id' => $pocketIdParam,
            'start_date' => $startDateParam,
            'end_date' => $endDateParam,
        ];

        return view('pages.dashboard', compact(
            'countCard',
            'transactionChart',
            'doughnutChart',
            'pieChart',
            'barChart',
            'categoryBarChart',
            'pockets',
            'filter'
        ));
    }
}
