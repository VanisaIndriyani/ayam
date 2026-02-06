<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable'],
            'price' => ['required', 'numeric'],
            'stock' => ['required', 'integer'],
            'weight' => ['required', 'integer'],
            'is_active' => ['boolean'],
            'images.*' => ['image', 'max:2048']
        ]);

        $product = Product::create([
            'category_id' => $validated['category_id'] ?? null,
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . uniqid(),
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'weight' => $validated['weight'],
            'is_active' => $request->boolean('is_active'),
        ]);

        // Upload gambar multiple
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $image) {
                $path = $image->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_primary' => $i === 0 ? true : false
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $product->load('images');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable'],
            'price' => ['required', 'numeric'],
            'stock' => ['required', 'integer'],
            'weight' => ['required', 'integer'],
            'is_active' => ['boolean'],
            'images.*' => ['image', 'max:2048']
        ]);

        $product->update([
            'category_id' => $validated['category_id'] ?? null,
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . uniqid(),
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'weight' => $validated['weight'],
            'is_active' => $request->boolean('is_active'),
        ]);

        // Upload gambar baru
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_primary' => false
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        // Hapus gambar
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
