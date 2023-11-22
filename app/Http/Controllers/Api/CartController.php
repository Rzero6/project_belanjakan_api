<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $carts = $carts = DB::table('carts')
                ->where('id_user', $user->id)
                ->get();
            if ($carts->isEmpty()) throw new \Exception('There is no item yet');
            return response()->json([
                'status' => true,
                'message' => 'Berhasil ambil data',
                'data' => $carts,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $cartData = $request->all();
            $user = Auth::user();
            $item = Item::find($cartData['id_item']);
            if (!$item) throw new \Exception('Item not found');
            if (!$user) throw new \Exception('User not found');
            $cartData['id_user'] = $user->id;
            $cart = Cart::updateOrCreate(['id_item' => $cartData['id_item'], 'id_user' => $user->id], $cartData);
            return response()->json([
                'status' => true,
                'message' => $cart->wasRecentlyCreated ? 'Successfully created data' : 'Successfully updated data',
                'data' => $cart,
                'amount' => $cart->amount,
                'item' => $cart->item,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $cart = Cart::find($id);
            if (!$cart) throw new \Exception('item not found');
            return response()->json([
                'status' => true,
                'message' => 'Berhasil ambil data',
                'data' => $cart
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $cart = Cart::find($id);
            if (!$cart) throw new \Exception('item not found');
            $cart->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Berhasil update data',
                'data' => $cart
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $cart = Cart::find($id);
            if (!$cart) throw new \Exception('item not found');
            $cart->delete();
            return response()->json([
                'status' => true,
                'message' => 'Berhasil delete data',
                'data' => $cart
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
