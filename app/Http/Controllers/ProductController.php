<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Http\Helper; // <--- ADDED THIS LINE: Import the Helper class

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['cat_info', 'sub_cat_info'])
                           ->orderBy('id', 'desc')
                           ->paginate(10);
        return view('backend.product.index', compact('products'));
    }

    public function create()
    {
        $brands = Brand::all();
        $categories = Category::where('is_parent', 1)->get();
        return view('backend.product.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'summary' => 'required|string',
                'description' => 'nullable|string',
                'photo' => 'required|string',
                'size' => 'nullable|array',
                'stock' => 'required|integer|min:0',
                'cat_id' => 'required|exists:categories,id',
                'child_cat_id' => 'nullable|exists:categories,id',
                'is_featured' => 'sometimes|boolean',
                'brand_id' => 'nullable|exists:brands,id',
                'status' => 'required|in:active,inactive',
                'condition' => 'required|in:default,new,hot',
                'price' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0|max:100', // Already nullable
            ]);

            $validated['slug'] = Helper::generateUniqueSlug($validated['title'], Product::class);
            $validated['is_featured'] = $request->has('is_featured');
            $validated['size'] = $request->has('size') ? implode(',', $request->size) : null;
            $validated['child_cat_id'] = $validated['child_cat_id'] ?? null;

            Product::create($validated);

            return redirect()->route('product.index')
                             ->with('success', 'Product created successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Product Store Error: '.$e->getMessage());
            return redirect()->back()->with('error', 'Error creating product: '.$e->getMessage());
        }
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $brands = Brand::all();
        $categories = Category::where('is_parent', 1)->get();
        return view('backend.product.edit', compact('product', 'brands', 'categories'));
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'summary' => 'required|string',
                'description' => 'nullable|string',
                'photo' => 'required|string',
                'size' => 'nullable|array',
                'stock' => 'required|integer|min:0',
                'cat_id' => 'required|exists:categories,id',
                'child_cat_id' => 'nullable|exists:categories,id',
                'is_featured' => 'sometimes|boolean',
                'brand_id' => 'nullable|exists:brands,id',
                'status' => 'required|in:active,inactive',
                'condition' => 'required|in:default,new,hot',
                'price' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0|max:100', // Already nullable
            ]);

            if ($product->title !== $validated['title']) {
                $validated['slug'] = Helper::generateUniqueSlug($validated['title'], Product::class);
            }

            $validated['is_featured'] = $request->has('is_featured');
            $validated['size'] = $request->has('size') ? implode(',', $request->size) : null;
            $validated['child_cat_id'] = $validated['child_cat_id'] ?? null;

            $product->update($validated);

            return redirect()->route('product.index')
                             ->with('success', 'Product updated successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Product Update Error: '.$e->getMessage());
            return redirect()->back()->with('error', 'Error updating product: '.$e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return redirect()->route('product.index')
                             ->with('success', 'Product deleted successfully');
        } catch (\Exception $e) {
            Log::error('Product Delete Error: '.$e->getMessage());
            return redirect()->back()->with('error', 'Error deleting product: '.$e->getMessage());
        }
    }
}
