<?php

namespace App\Http\Controllers;

use DateTime;
use App\Model\Transaction;
use Illuminate\Http\Request;
use App\Http\Requests\TransferRequest;
use App\Http\Requests\TransactionRequest;
use App\Http\Requests\PayTransactionRequest;
use App\Model\BankAccount;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($month = null, $year = null)
    {
        if(!isset($month) && !isset($year)) {
            $month = date('m');
            $year = date('Y');
        }

        return Transaction::whereMonth('due_at', $month)
                    ->with(['category:id,name', 'account:id,name'])
                    ->whereYear('due_at', $year)
                    ->orderBy('due_at', 'desc')
                    ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionRequest $request)
    {
        $data = $request->all();
        $data['due_at'] = date('Y-m-d', strtotime($data['due_at']));

        $transaction = Transaction::create($data);

        return $transaction;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Transaction::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TransactionRequest $request, $id)
    {
        $data = $request->all();
        $data['due_at'] = date('Y-m-d', strtotime($data['due_at']));

        $transaction = Transaction::find($id);
        $transaction->update($data);

        return $transaction;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Transaction::find($id)->delete();
    }

    public function payTransaction(PayTransactionRequest $request, $id)
    {
        $data = $request->all();

        $transaction = Transaction::find($id);
        $transaction->update($data);

        return $transaction;
    }

    public function transfer(TransferRequest $request) {
        $data = $request->all();
        $from = BankAccount::find($data['from']);
        $to = BankAccount::find($data['to']);

        $transaction1 = Transaction::create([
            'description' => 'Transfer from '.$from->name.' to '.$to->name,
            'amount' => $data['amount'],
            'type' => 'Transfer',
            'due_at' => $data['due_at'],
            'category_id' => $data['category_id'],
            'account_id' => $data['from'],
            'payed' => $data['payed'],
        ]);
        $transaction2 = Transaction::create([
            'description' => 'Transfer from '.$from->name.' to '.$to->name,
            'amount' => $data['amount'],
            'type' => 'Transfer',
            'due_at' => $data['due_at'],
            'category_id' => $data['category_id'],
            'account_id' => $data['to'],
            'payed' => $data['payed'],
        ]);

        $returnData = [$transaction1, $transaction2];
        return response()->json($returnData, 201);
    }
}
