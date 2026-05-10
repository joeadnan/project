<?php

namespace App\Http\Controllers\Api\Customer;
use App\Models\Review;
use App\Http\Resources\ReviewResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class ReviewController extends Controller
{
    /**
    * store
     *
     * 
    @param  mixed $request
     * @return void
     */

public function store(Request $request)
{
    // 1. Validasi input agar rating wajib angka 1-5
    $request->validate([
        'rating'     => 'required|integer|min:1|max:5',
        'review'     => 'required',
        'product_id' => 'required|exists:products,id',
        'order_id'   => 'required|exists:orders,id',
    ]);

    // 2. Cek apakah sudah pernah review
    $check_review = Review::where('order_id', $request->order_id)
                          ->where('product_id', $request->product_id)
                          ->first();

    if($check_review) {
        return response()->json([
            'success' => false,
            'message' => 'Anda sudah memberikan review untuk produk ini.'
        ], 409);
    }

    // 3. Simpan Review
    $review = Review::create([
        'rating'      => $request->rating,
        'review'      => $request->review,
        'product_id'  => $request->product_id,
        'order_id'    => $request->order_id,
        'customer_id' => auth()->guard('api_customer')->user()->id
    ]);

    if($review) {
        return new ReviewResource(true, 'Data Review Berhasil Disimpan!', $review);
    }

    return new ReviewResource(false, 'Data Review Gagal Disimpan!', null);
}
}