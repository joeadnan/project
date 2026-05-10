<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request) 
    {
        $products = Product::with('category')
            ->when($request->q, function($query, $q) {
                return $query->where('title', 'like', '%' . $q . '%');
            })->latest()->paginate(5); 

        return new ProductResource(true, 'list data products', $products);
    } 

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
    'title'       => 'required|unique:products,title,' . ($product->id ?? ''),
    'category_id' => 'required|exists:categories,id', // Tambahkan exists:categories,id
    'description' => 'required',
    'weight'      => 'required',
    'price'       => 'required',
    'stock'       => 'required',
    'discount'    => 'required',
    'image'       => $request->isMethod('post') ? 'required|image|mimes:jpeg,jpg,png|max:2000' : 'nullable|image|mimes:jpeg,jpg,png|max:2000',
]);
        // $validator = Validator::make($request->all(), [
        //     'image'       => 'required|image|mimes:jpeg,jpg,png|max:2000',
        //     'title'       => 'required|unique:products',
        //     'category_id' => 'required',
        //     'description' => 'required',
        //     'weight'      => 'required',
        //     'price'       => 'required',
        //     'stock'       => 'required',
        //     'discount'    => 'required'
        // ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());

        $product = Product::create([
            'image'       => $image->hashName(),
            'title'       => $request->title,
            'slug'        => Str::slug($request->title, '-'),
            'category_id' => $request->category_id,
            'user_id'     => auth()->guard('api_admin')->user() ? auth()->guard('api_admin')->user()->id : null,
            'description' => $request->description,
            'weight'      => $request->weight,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'discount'    => $request->discount
        ]);

        return new ProductResource(true, 'Data Produk Berhasil Disimpan!', $product);
    }

    public function show($id)
    {
        $product = Product::with('category')->whereId($id)->first();
        if ($product) {
            return new ProductResource(true, 'Detail Data Produk Ditemukan!', $product);
        }
        return new ProductResource(false, 'Detail Data Produk Tidak Ditemukan!', null);
    }

public function update(Request $request, Product $product)
{
    $validator = Validator::make($request->all(), [
        'title'       => 'required|unique:products,title,' . $product->id,
        'category_id' => 'required',
        'description' => 'required',
        'weight'      => 'required',
        'price'       => 'required',
        'stock'       => 'required',
        'discount'    => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    // Data dasar tanpa gambar
    $data = [
        'title'       => $request->title,
        'slug'        => Str::slug($request->title, '-'),
        'category_id' => $request->category_id,
        'user_id'     => auth()->guard('api_admin')->user()->id,
        'description' => $request->description,
        'weight'      => $request->weight,
        'price'       => $request->price,
        'stock'       => $request->stock,
        'discount'    => $request->discount,
    ];

    // Tambahkan gambar baru jika ada
    if ($request->file('image')) {
        // Hapus gambar lama
        Storage::disk('local')->delete('public/products/' . basename($product->image));

        // Upload gambar baru
        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());

        $data['image'] = $image->hashName();
    }

    $product->update($data);

    return response()->json([
        'success' => true,
        'message' => 'Product updated successfully',
        'data'    => $product
    ]);
}

    public function destroy( $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Data Tidak Ditemukan'], 404);
        }
        if ($product->image) {
    Storage::disk('local')->delete('public/products/' . basename($product->image));
        }
    $product->delete();

    return response()->json([
        'success' => true,
        'message' => 'Product berhasil dihapus'
    ], 200);
    }
}
