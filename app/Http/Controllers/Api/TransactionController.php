<?php

namespace App\Http\Controllers\Api;

use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Repositories\Repository;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionItemResource;

class TransactionController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->resource = TransactionItemResource::class;
        $this->model = app(Transaction::class);
        $this->repositry =  new Repository($this->model);
    }

    public function transaction(TransactionRequest $request){
        // try {
        //     DB::beginTransaction();

            if($request->type == "Deposite"){
                $transaction = $this->createTransaction($request);
                $total = $transaction->wallet->total + (int)$request->amount;
                if ($total <=  $transaction->wallet->total) {
                    $transaction->update(['status'=>'Failed']);
                    return $this->returnError("Fail depositing!");
                }
                $transaction->wallet->update(['total' => $total]);
                $transaction->update(['status'=>'Success']);
                return $this->returnData("Transaction",new TransactionItemResource($transaction), "Success Depositing!");
            }
            if($request->type == "Withdraw"){
                $transaction = $this->createTransaction($request);
                $total = $transaction->wallet->total -  (int)$request->amount;
                if ($total <= 0 || $transaction->wallet->total <= 0) {
                    $transaction->update(['status'=>'Failed']);
                    return $this->returnError("Credit is not enough!");
                }
                $transaction->wallet->update(['total' => $total]);
                $transaction->update(['status'=>'Success']);
                return $this->returnData("Transaction",new TransactionItemResource($transaction), "Success Withdarawing!");
            }

            DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return $this->returnError("Error! $e");
        // }
    }

    public function createTransaction($request){
        $request->status = "Processing";
        return $this->model->create($request->all());
    }
}
