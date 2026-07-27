<?php

namespace App\Http\Controllers;

use App\Models\Pocket;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request){
        $pockets = Pocket::query()->where('user_id', $request->user()->id)->get();
        $totalAmount = $pockets->sum('amount');
        $primaryPocket = $pockets->firstWhere('is_primary', true);
        $totalPocket = $primaryPocket ? $primaryPocket->amount : 0;
        $transactions = Transaction::query()->where('user_id', $request->user()->id)->get();
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');

        $countCard = [
            'total' => $totalAmount,
            'pocket' => $totalPocket,
            'income' => $totalIncome,
            'expense' => $totalExpense
        ];

        $incomeChartData = [];
        $expenseChartData = [];
        $transactionChartLabel = [];

        $groupedTransactions = $transactions
        ->where('type', '!=', 'transfer')
        ->groupBy(function ($item) {
            return $item->created_at->format('d/m/Y');
        });

        foreach ($groupedTransactions as $date => $items) {
            $transactionChartLabel[] = $date;

            $incomeChartData[] = $items->where('type', 'income')->sum('amount');
            $expenseChartData[] = $items->where('type', 'expense')->sum('amount');
        }

        $transactionChart = [
            'income' => $incomeChartData,
            'expense' => $expenseChartData,
            'label' => $transactionChartLabel
        ];

        return view('pages.dashboard', compact('countCard', 'transactionChart'));
    }
}
