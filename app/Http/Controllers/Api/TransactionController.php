<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index()
    {
        try {
            $userId = Auth::user()->id;
            $transactions = Transaction::where('id_buyer', $userId)->get();
            return response()->json([
                'status' => true,
                'message' => 'Berhasil ambil data',
                'data' => $transactions
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
            $userId = Auth::user()->id;
            $transactionData = $request->all();

            $validate = Validator::make($transactionData, [
                'address' => 'required',
                'discount' => 'required',
                'payment_method' => 'required',
                'delivery_cost' => 'required',
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()], 400);
            }
            $transactionData['status'] = 'ordered';
            $transactionData['id_buyer'] = $userId;

            $transaction = Transaction::create($transactionData);
            return response()->json([
                'status' => true,
                'message' => 'Berhasil insert data',
                'data' => $transaction
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    public function show($id)
    {
        try {
            $transaction = Transaction::find($id);
            if (!$transaction) throw new \Exception('Transaksi tidak ditemukan');
            return response()->json([
                'status' => true,
                'message' => 'Berhasil ambil data',
                'data' => $transaction
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $transaction = Transaction::find($id);
            if (!$transaction) {
                throw new \Exception('Transaksi tidak ditemukan');
            }

            $validate = Validator::make($request->all(), [
                'status' => 'required|in:ordered,ondelivery,delivered|alpha',
            ]);

            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()], 400);
            }

            $transaction->status = strtolower($request->status);
            $transaction->save();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil update data',
                'data' => $transaction
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
