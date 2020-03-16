<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Model\BankAccount;
use App\Model\Transaction;
use Illuminate\Http\Request;
use App\Model\Useful\DateConversion;
use App\Http\Requests\TransferRequest;
use App\Http\Requests\TransactionRequest;
use App\Http\Requests\PayTransactionRequest;
use App\Http\Requests\TransactionEditRequest;
use App\Model\RecurringTransaction;

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
        $transactions = [];

        $transaction = Transaction::create($data);

        if(
            (!isset($data['repeat']) || $data['repeat'] == false) && 
            (!isset($data['recurring']) || $data['recurring'] == false)
        )
            return $transaction;
        else {
            $firstTransactionId = $transaction->id;

            $transaction->first_transaction = $firstTransactionId;
            $transaction->save();
            $transactions[] = $transaction;

            $data['first_transaction'] = $firstTransactionId;
            $data['payed'] = false;
            $date = Carbon::createFromFormat('Y-m-d', $data['due_at'])->format('Y-m-d');

            if(isset($data['repeat']) && $data['repeat'] == true) {
                for($i=1; $i<$data['repeatTimes']; $i++) {
                    $date = DateConversion::newDateByPeriod($date, $data['period'])->toDateString();
                    $transactions[] = $this->createRecurringTransaction($data, $date);
                }
            }
            else if(isset($data['recurring']) && $data['recurring'] == true){
                $transaction->is_recurring = true;
                $transaction->save();

                $endofYear = Carbon::createFromFormat('Y-m-d', $date)->endOfYear();
                $lastDate = $date; //Used to get the last date to create recurring model
                $date = DateConversion::newDateByPeriod($date, $data['period'])->toDateString();

                while(Carbon::createFromFormat('Y-m-d', $date)->isBefore($endofYear)) {
                    $lastDate = $date; //Used to get the last date to create recurring model
                    $transactions[] = $this->createRecurringTransaction($data, $date);
                    $date = DateConversion::newDateByPeriod($date, $data['period'])->toDateString();
                }

                $data['last_date'] = $lastDate;
                $this->createRecurringTransactionModel($data);
            }

            return response()->json($transactions, 201);
        }
    }

    private function createRecurringTransaction($transactionData, $date) {
        $transactionData['due_at'] = $date;
        $transactionData['is_recurring'] = true;
        
        $transaction = Transaction::create($transactionData);
        $transaction->save();

        return $transaction;
    }

    private function createRecurringTransactionModel($data) {
        RecurringTransaction::create($data);
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
    public function update(TransactionEditRequest $request, $id)
    {
        $data = $request->all();

        $transaction = Transaction::find($id);
        if(!isset($transaction->first_transaction) || (isset($data['repeatCount']) && $data['repeatCount'] == 'this')) {
            $transaction->update($data);

            return $transaction;
        }
        else {
            $transactions = [];

            if($data['repeatCount'] == 'all')
                $transactions = Transaction::where('first_transaction', $transaction->first_transaction)->get();
            else if($data['repeatCount'] == 'next')
                $transactions = Transaction::where('first_transaction', $transaction->first_transaction)
                                    ->where('due_at', '>=', $transaction->due_at)
                                    ->get();
            
            foreach($transactions as $t) {
                $data['due_at'] = $t->due_at->toDateString();
                $t->update($data);
            }

            //Update Recurring Model
            if($transaction->is_recurring == true) {
                $recurring = RecurringTransaction::where('first_transaction', $transaction->first_transaction)->get()->first();
                $data['last_date'] = $recurring->last_date->toDateString();
                $recurring->update($data);
            }
            
            return response()->json($transactions, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $transactionCount = null)
    {
        if(!isset($transactionCount) || $transactionCount == 'this')
            Transaction::find($id)->delete();
        else if($transactionCount == 'all'){
            $transaction = Transaction::find($id);

            $this->deleteRecurring($transaction);

            Transaction::where('first_transaction', $transaction->first_transaction)->delete();
        }
        else if($transactionCount == 'next') {
            $transaction = Transaction::find($id);

            $this->deleteRecurring($transaction);

            Transaction::where('first_transaction', $transaction->first_transaction)
                ->where('due_at', '>=', $transaction->due_at)
                ->delete();
        }
    }

    private function deleteRecurring($transaction)
    {
        RecurringTransaction::where('first_transaction', $transaction->first_transaction)->delete();
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
            'type' => 'Expense',
            'is_transfer' => true,
            'due_at' => $data['due_at'],
            'category_id' => $data['category_id'],
            'account_id' => $data['from'],
            'payed' => $data['payed'],
        ]);
        $transaction1->first_transaction = $transaction1->id;
        $transaction1->save();

        $transaction2 = Transaction::create([
            'description' => 'Transfer from '.$from->name.' to '.$to->name,
            'amount' => $data['amount'],
            'type' => 'Income',
            'is_transfer' => true,
            'due_at' => $data['due_at'],
            'category_id' => $data['category_id'],
            'account_id' => $data['to'],
            'payed' => $data['payed'],
            'first_transaction' => $transaction1->id,
        ]);

        $returnData = [$transaction1, $transaction2];
        return response()->json($returnData, 201);
    }
}
