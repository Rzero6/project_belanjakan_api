<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function showPerItem($id)
    {
        try {
            $reviews = Review::where('id_item', $id)->get();
            return response()->json([
                'status' => true,
                'message' => 'Berhasil ambil data',
                'data' => $reviews
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
            $reviewData = $request->all();

            $validate = Validator::make($reviewData, [
                'id_item' => 'required',
                'rating' => 'required',
                'detail' => 'required',
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()], 400);
            }
            $itemData = Item::find($reviewData['id_item']);
            if (!$itemData) throw new \Exception('Item tidak ditemukan');
            $reviewData['id_user'] = $userId;

            $review = Review::create($reviewData);
            return response()->json([
                'status' => true,
                'message' => 'Berhasil insert data',
                'data' => $review
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
            $review = Review::find($id);
            if (!$review) throw new \Exception('Review tidak ditemukan');
            return response()->json([
                'status' => true,
                'message' => 'Berhasil ambil data',
                'data' => $review
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
            $review = Review::find($id);
            if (!$review) throw new \Exception('Barang tidak ditemukan');
            $updatedData = $request->all();
            $validate = Validator::make($updatedData, [
                'id_item' => 'required',
                'rating' => 'required',
                'detail' => 'required',
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()], 400);
            }
            $review->update($updatedData);
            return response()->json([
                'status' => true,
                'message' => 'Berhasil update data',
                'data' => $review
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
            $review = Review::find($id);
            if (!$review) throw new \Exception('Review tidak ditemukan');
            $review->delete();
            return response()->json([
                'status' => true,
                'message' => 'Berhasil delete data',
                'data' => $review
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
