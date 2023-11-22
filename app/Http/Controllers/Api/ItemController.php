<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $items = Item::all();
            return response()->json([
                'status' => true,
                'message' => 'Berhasil ambil data',
                'data' => $items
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
            $userId = Auth::user()->id;
            $itemData = $request->all();
            $itemData['id_seller'] = $userId;
            $item = Item::create($itemData);
            return response()->json([
                'status' => true,
                'message' => 'Berhasil insert data',
                'data' => $item
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
            $item = Item::find($id);
            if (!$item) throw new \Exception('Barang tidak ditemukan');
            return response()->json([
                'status' => true,
                'message' => 'Berhasil ambil data',
                'data' => $item
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
            $item = Item::find($id);
            if (!$item) throw new \Exception('Barang tidak ditemukan');
            $item->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Berhasil update data',
                'data' => $item
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
            $item = Item::find($id);
            if (!$item) throw new \Exception('Barang tidak ditemukan');
            $item->delete();
            return response()->json([
                'status' => true,
                'message' => 'Berhasil delete data',
                'data' => $item
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    public function showByName($searchTerm)
    {
        try {
            $items = Item::where('name', 'like', '%' . $searchTerm . '%')->get();

            if ($items->isEmpty()) {
                throw new \Exception('No items found with the specified search term');
            }

            return response()->json([
                'status' => true,
                'message' => 'Successfully retrieved data',
                'data' => $items
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    public function showBySeller($searchTerm)
    {
        try {
            $items = Item::where('id_seller', '=', Auth::id())->where('name', 'like', '%' . $searchTerm . '%')->get();

            if ($items->isEmpty()) {
                throw new \Exception('No items found with the specified search term');
            }

            return response()->json([
                'status' => true,
                'message' => 'Successfully retrieved data',
                'data' => $items
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
