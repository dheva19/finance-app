<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\TransactionStoreRequest;
use App\Http\Requests\Transaction\TransactionUpdateRequest;
use App\Models\Pocket;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $transactions = Transaction::query()->where('user_id', $request->user()->id)
        ->when(
            $request->input('search'), function ($query, $search) {
                $query->where('transaction_number', 'like', '%'.$search.'%');
            }
        )
        ->when(
            $request->input('type'), function ($query, $type) {
                $query->where('type', $type);
            }
        )
        ->when(
            $request->input('date'), function ($query, $date) {
                $query->whereDate('created_at', $date);
            }
        )
        ->when(
            $request->input('pocket'), function($query, $pocket){
                $query->where('from_pocket_id', $pocket)->orWhere('to_pocket_id', $pocket);
            }
        )
        ->orderBy('created_at', 'desc')
        ->paginate(10)->withQueryString();

        $pockets = Pocket::where('user_id', $request->user()->id)->get();
        return view('pages.transactions.index', compact('transactions', 'pockets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $pockets = Pocket::where('user_id', $request->user()->id)->get();
        return view('pages.transactions.create', compact('pockets'));
    }

    function generateTransactionNumber(string $prefix, string $userId){
        $currentDate = now()->format('dmY');
        $transactionNumber = $prefix . $userId . "-" . $currentDate . "-";
        $latestTransaction = Transaction::where('transaction_number', 'like', "%".$transactionNumber."%")->latest()->first();
        if($latestTransaction){
            $uniqueNumber = Str::substr($latestTransaction->transaction_number, 14, 3);
            $uniqueNumber = Number::parseInt($uniqueNumber) + 1;
        }else{
            $uniqueNumber = 1;
        }
        $formattedCountUnque = sprintf('%03d', $uniqueNumber);
        return $transactionNumber .= $formattedCountUnque;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionStoreRequest $request)
    {
        $requestData = $request->validated();
        $requestData['user_id'] = $request->user()->id;

        if(isset($requestData['from_pocket_id'])) $fromPocket = Pocket::findOrFail($requestData['from_pocket_id']);
        if(isset($requestData['to_pocket_id'])) $toPocket = Pocket::findOrFail($requestData['to_pocket_id']);

        $prefix = "";
        if($requestData['type'] == "income") {
            $toPocket->amount += $requestData['amount'];
            $prefix = "INC";
        }else if($requestData['type'] == "expense") {
            if($requestData['amount'] > $fromPocket->amount){
                return back()->with('error', 'Transaksi gagal karena saldo tidak cukup!')->withInput();
            }
            $fromPocket->amount -= $requestData['amount'];
            $prefix = "EXP";
        }else if($requestData['type'] == "transfer") {
            if($requestData['amount'] > $fromPocket->amount){
                return back()->with('error', 'Transaksi gagal karena saldo tidak cukup!')->withInput();
            }
            $fromPocket->amount -= $requestData['amount'];
            $toPocket->amount += $requestData['amount'];
            $prefix = "TRA";
        }
        $requestData['transaction_number'] = $this->generateTransactionNumber($prefix, $request->user()->id);

        Transaction::create($requestData);

        if(isset($requestData['from_pocket_id'])) $fromPocket->save();
        if(isset($requestData['to_pocket_id'])) $toPocket->save();

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionUpdateRequest $request, string $id)
    {
        $requestData = $request->validated();
        $transaction = Transaction::findOrFail($id);
        if($transaction->user_id != $request->user()->id){
            return abort(404);
        }
        $transaction->update($requestData);
        return back()->with('success', "Transaksi berhasil diupdate!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $transaction = Transaction::findOrFail($id);
        if($transaction->user_id != $request->user()->id){
            return abort(404);
        }
        $transaction->delete();
        return back()->with('success', 'Transaksi berhasil dihapus!');
    }
}
