<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('user')->paginate(20);
        return response()->json($products);
    }

    public function myProducts()
    {
        $products = Product::where('user_id', Auth::id())->paginate(20);
        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('products');
            }
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'in_stock' => $request->in_stock ?? true,
            'stock_count' => $request->stock_count ?? 0,
            'images' => $images,
            'user_id' => Auth::id(),
        ]);

        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $images = $product->images;
        if ($request->hasFile('images')) {
            foreach ($images as $image) {
                Storage::delete($image);
            }

            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('products');
            }
        }

        $product->update($request->all() + ['images' => $images]);

        return response()->json($product);
    }


    public function destroy($id)
    {
        $product = Product::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        foreach ($product->images as $image) {
            Storage::delete($image);
        }
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ], 200);
    }
}
