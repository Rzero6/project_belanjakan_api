<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $category = Category::all();
            return response()->json([
                'status' => true,
                'message' => 'Berhasil ambil data',
                'data' => $category
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
            $categoryData = $request->all();

            $validate = Validator::make($categoryData, [
                'name' => 'required',
                'image' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()], 400);
            }
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath = Storage::disk('railway')->put('images/category/', $image);
                $categoryData['image'] = Storage::url($imagePath);
                // $renameImage = time() . '.' . $image->getClientOriginalExtension();
                // $destinationPath = "/images/category/";
                // $image->move(public_path($destinationPath), $renameImage);
                // $imagePath = $destinationPath . $renameImage;
                // $categoryData['image'] = $imagePath;
            }

            $category = Category::create($categoryData);
            return response()->json([
                'status' => true,
                'message' => 'Berhasil insert data',
                'data' => $category
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
            $category = category::find($id);
            if (!$category) throw new \Exception('Category tidak ditemukan');
            return response()->json([
                'status' => true,
                'message' => 'Berhasil ambil data',
                'data' => $category
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
            $category = Category::find($id);
            if (!$category) throw new \Exception('Category tidak ditemukan');
            $categoryData = $request->only(['name', 'image']);

            $validate = Validator::make($categoryData, [
                'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            ]);

            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()], 400);
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $renameImage = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = "/images/category/";
                $image->move(public_path($destinationPath), $renameImage);
                $imagePath = $destinationPath . $renameImage;

                // Delete the old image if it exists
                if ($category->image) {
                    $oldImagePath = public_path($category->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $categoryData['image'] = $imagePath;
            }

            foreach ($categoryData as $key => $value) {
                if (!is_null($value)) {
                    $category->{$key} = $value;
                }
            }

            $category->save();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil update data',
                'data' => $category
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::find($id);
            if (!$category) throw new \Exception('Category tidak ditemukan');

            // Delete the category image if it exists
            if ($category->image) {
                $imagePath = public_path($category->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $category->delete();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menghapus data kategori',
                'data' => []
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
