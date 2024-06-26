<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\ImageFile;

use function PHPUnit\Framework\isNan;

class ItemController extends Controller
{
    private function base64_to_jpeg($base64_string, $output_file)
    {
        $file = base64_decode($base64_string);
        $img_file = public_path('/images/item') . "/$output_file";
        file_put_contents($img_file, $file);
    }

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

            $validate = Validator::make($itemData, [
                'name' => 'required',
                'detail' => 'required',
                'image' => 'required',
                'price' => 'required',
                'stock' => 'required|numeric',
                'id_category' => 'required',
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()], 400);
            }
            $categoryData = Category::find($itemData['id_category']);
            if (!$categoryData) throw new \Exception('Category tidak ditemukan');

            $itemData['id_seller'] = $userId;
            $imageName = time() . '.jpg';
            $this->base64_to_jpeg($request->image, $imageName);
            $itemData['image'] = '/images/item/' . $imageName;

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

    public function showByCat($id)
    {
        try {
            $item = Item::where('id_category', $id)->get();
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
            $updatedData = $request->all();
            if (!$item) throw new \Exception('Barang tidak ditemukan');
            $validate = Validator::make($updatedData, [
                'name' => 'required',
                'detail' => 'required',
                'price' => 'required',
                'stock' => 'required|numeric',
                'id_category' => 'required',
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()], 400);
            }
            if ($request->image && strpos($request->image, '/images/item') !== 0) {
                $imageName = time() . '.jpg';
                $this->base64_to_jpeg($request->image, $imageName);
                $updatedData['image'] = '/images/item/' . $imageName;
                if (file_exists(public_path($item->image))) {
                    unlink(public_path($item->image));
                }
            }
            $item->update($updatedData);
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


    public function updateStock(Request $request, $id)
    {
        try {
            $item = Item::find($id);
            if (!$item) throw new \Exception('Barang tidak ditemukan');
            $amount = $request['amount'];
            if ($amount <= 0) {
                throw new \Exception('Jumlah harus lebih dari nol');
            }
            if ($item->stock < $amount) {
                throw new \Exception('Jumlah stok tidak mencukupi');
            }
            $updatedStock = $item->stock - $amount;
            $item->stock = $updatedStock;
            $item->save();
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
            if (file_exists(public_path($item->image))) {
                unlink(public_path($item->image));
            }
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

    public function showByName($id, $searchTerm = null)
    {
        try {
            if ($id == 0) {
                $items = Item::where('name', 'like', '%' . $searchTerm . '%')->get();
            } else {
                $items = Item::where('name', 'like', '%' . $searchTerm . '%')->where('id_category', $id)->get();
            }

            if ($items->isEmpty()) {
                throw new \Exception('No items found with the specified search term');
            }

            return response()->json([
                'status' => true,
                'message' => 'Successfully retrieved data',
                'id_category' => $id,
                'data' => $items,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'id_category' => $id,
                'data' => []
            ], 400);
        }
    }

    public function showOnlyToOwnerByName($searchTerm = null)
    {
        try {
            $user = Auth::user();
            if (!$user) throw new \Exception('User not found');
            $items = Item::where('id_seller', $user->id)->where('name', 'like', '%' . $searchTerm . '%')->get();

            if ($items->isEmpty()) {
                throw new \Exception('No items');
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
