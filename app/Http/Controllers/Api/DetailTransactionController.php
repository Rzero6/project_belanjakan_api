<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailTransaction;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DetailTransactionController extends Controller
{
    public function showByTransaction($id)
    {
        try {
            $detailTransactions = DetailTransaction::where('id_transaction', $id)->get();
            if (!$detailTransactions) throw new \Exception('Detail Transaksi tidak ditemukan');
            return response()->json([
                'status' => true,
                'message' => 'Successfully retrieved data',
                'data' => $detailTransactions
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $detailTransactionData = $request->all();
            $validate = Validator::make($detailTransactionData, [
                'id_transaction' => 'required',
                'name' => 'required',
                'price' => 'required',
                'amount' => 'required',
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()], 400);
            }
            $transaction = Transaction::find($detailTransactionData['id_transaction']);
            if (!$transaction) throw new \Exception('Detail Transaksi tidak ditemukan');
            $detailTransactionData['rated'] = false;
            $detailTransaction = DetailTransaction::create($detailTransactionData);
            return response()->json([
                'status' => true,
                'message' => 'Berhasil insert data',
                'data' => $detailTransaction
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    public function update($id)
    {
        try {
            $detailTransaction = DetailTransaction::find($id);
            if (!$detailTransaction) throw new \Exception('Detail Transaksi tidak ditemukan');
            $detailTransaction['rated'] = true;
            $detailTransaction->save();
            return response()->json([
                'status' => true,
                'message' => 'Berhasil update data',
                'data' => $detailTransaction
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }
}
