<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function index()
    {
        try {

            $user = Auth::user();
            $coupons = DB::table('coupons')
                ->where('id_user', $user->id)
                ->get();
            if ($coupons->isEmpty()) throw new \Exception('There is no Coupon yet');
            return response()->json([
                'status' => true,
                'message' => 'Berhasil ambil data',
                'data' => $coupons
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $couponData = $request->all();
            $user = User::find($request->id_user);

            if (!$user) throw new \Exception('User not found');
            $coupon = Coupon::create($couponData);
            return response()->json([
                'status' => true,
                'message' => 'Berhasil insert data',
                'data' => $coupon
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
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $coupon = Coupon::find($id);
            if (!$coupon) throw new \Exception('Coupon not found');
            return response()->json([
                'status' => true,
                'message' => 'Berhasil ambil data',
                'data' => $coupon
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
            $coupon = Coupon::find($id);
            if (!$coupon) throw new \Exception('Coupon not found');
            $coupon->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Berhasil update data',
                'data' => $coupon
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
            $coupon = Coupon::find($id);
            if (!$coupon) throw new \Exception('Coupon not found');
            $coupon->delete();
            return response()->json([
                'status' => true,
                'message' => 'Berhasil delete data',
                'data' => $coupon
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
