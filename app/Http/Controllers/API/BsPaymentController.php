<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;

class BsPaymentController extends Controller
{
    //api mock response

    public function mockResponse(){
       
        return response()->json([
            'status'  => 'accepted',
            'message' => 'Successfully Done',
        ]);
    }

    // Payment form user
    public function payment(Request $request){
        $request->validate([
            'amount' => 'required|numeric',
            'date'   => 'required'
        ]);
        try {
            DB::beginTransaction();
            $user = Auth::user();
          
            
            $payment = new Payment();
            $payment->user_id = $user->id;
            $payment->amount  = $request->amount;
            $payment->date    = $request->date;
            $payment->status  = 0;
            $payment->save();

            $transaction = new Transaction();
            $transaction->payment_id = $payment->id;
            $transaction->status = 0;
            $transaction->received_by = 0;
            $transaction->save();
            DB::commit();
            $response = [
                'status'  => 'accepted',
                'message' => 'Payment Successfull',
            ];
            
            return $this->sendSuccess("success", JsonResponse::HTTP_OK, $response);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->sendError("error", JsonResponse::HTTP_OK, $ex->getMessage());
        }
    }


    public function paymentApproval(Request $request){
        $request->validate([
            'payment_id' => 'required',
            'status'     => 'required'
        ]);
        try {
            DB::beginTransaction();
            $user = Auth::user();
          
            
            $payment = Payment::find($request->payment_id);
            $payment->status  = $request->status;
            $payment->save();

            $transaction = Transaction::where('payment_id',$payment->id);
            $transaction->payment_id = $payment->id;
            $transaction->status = $request->status;
            $transaction->received_by = $user->id;
            $transaction->save();
            DB::commit();
            $response = [
                'status'  => 'accepted',
                'message' => 'Payment Successfully'.($request->status == 1?'Received':'Canceled'),
            ];
            
            return $this->sendSuccess("success", JsonResponse::HTTP_OK, $response);
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->sendError("error", JsonResponse::HTTP_OK, $ex->getMessage());
        }
    }
}
