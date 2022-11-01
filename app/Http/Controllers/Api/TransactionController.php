<?php

namespace App\Http\Controllers\Api;

use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Repositories\Repository;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
        try {

            if($request->type == "Deposite"){
                DB::beginTransaction();

                $transaction = $this->createTransaction($request);
                $wallet = Wallet::find($transaction->wallet->id);
                $total = $wallet->total + $request->amount;
                if ((int)$total <=  (int)$transaction->wallet->total) {
                    $transaction->update(['status'=>'Failed']);
                    return $this->returnError("Fail depositing!");
                }
                $wallet->update(['total'=>(double)$total]);
                $transaction->update(['status'=>'Success']);
                DB::commit();
                return $this->returnData("Transaction",new TransactionItemResource($transaction), "Success Depositing!");
            }elseif($request->type == "Withdraw"){
                DB::beginTransaction();

                $transaction = $this->createTransaction($request);
                $wallet = Wallet::find($transaction->wallet->id);
                $total = $wallet->total - $request->amount;
                if ($total < 0 || $transaction->wallet->total <= 0) {
                    $transaction->update(['status'=>'Failed']);
                    return $this->returnError("Credit is not enough!");
                }
                $wallet->update(['total'=>(double)$total]);
                $transaction->update(['status'=>'Success']);
                DB::commit();
                return $this->returnData("Transaction",new TransactionItemResource($transaction), "Success Withdarawing!");
            }

        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError("Error! $e");
        }
    }

    public function createTransaction($request){
        $request->status = "Processing";
        return $this->model->create($request->all());
    }
}
