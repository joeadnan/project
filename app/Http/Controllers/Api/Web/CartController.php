<?php

namespace App\Http\Controllers\Api\Web;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as Controller;
use App\Http\Resources\CartResource;
class CartController extends Controller
{
/**
     * __construct
     *
     * 
    * @return void
     */
    public function __construct()
    {
    $this->middleware('auth:api_customer');
    }
/**
     * Display a listing of the resource.
     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carts = Cart::with('product')
                ->where('customer_id',auth()->guard('api_customer')->user()->id)
                ->latest()
                ->get();
    //return with Api Resource
    return new CartResource(true, 'List Data Carts', $carts);
    }
    /**
     * Store a newly created resource in storage.
     *
     * 
    *@param  \Illuminate\Http\Request  $request
     * 
     *  @return \Illuminate\Http\Response
     */

public function store(Request $request)
{
    // Ambil data dari request, beri default 1 jika qty kosong
    $qty = $request->qty ?? 1;

    $item = Cart::where('product_id', $request->product_id)
                ->where('customer_id', auth()->guard('api_customer')->user()->id)
                ->first();

    if ($item) {
        // Jika barang sudah ada di keranjang, tambah qty-nya
        $newQty = $item->qty + $qty;
        
        $item->update([
            'qty'    => $newQty,
            'price'  => $request->price * $newQty,
            'weight' => $request->weight * $newQty
        ]);
    } else { 
        //     $item = new Cart;
        // $item->product_id  = $request->product_id;
        // $item->customer_id = auth()->guard('api_customer')->user()->id;
        // $item->qty         = $request->qty ?? 1; // Memastikan qty tidak null
        // $item->price       = $request->price * ($request->qty ?? 1);
        // $item->weight      = $request->weight * ($request->qty ?? 1);
        // $item->save();

        // Jika barang belum ada, buat baru
        $item = Cart::create([
            'product_id'  => $request->product_id,
            'customer_id' => auth()->guard('api_customer')->user()->id,
            'qty'         => $qty, // Dipastikan tidak null karena ada ?? 1 diatas
            'price'       => $request->price * $qty,
            'weight'      => $request->weight * $qty
        ]);
    }

    return new CartResource(true, 'Success Add To Cart', $item);
}


/**
     * getCartPrice
     *
     * @return void
     */
    public function getCartPrice()
    {
        $totalPrice = Cart::with('product')
            ->where('customer_id',auth()->guard('api_customer')->user()->id)
            ->sum('price');
   //return with Api Resource
        return new CartResource(true, 'Total Cart Price', $totalPrice);
    }
    /**
     * getCartWeight
     *
     * @return void
     */
    public function getCartWeight()
    {
        $totalWeight = Cart::with('product')
        ->where('customer_id',auth()->guard('api_customer')->user()->id)
        ->sum('weight');
        //return with Api Resource
        return new CartResource(true, 'Total Cart Weight',
        $totalWeight);
    }
    /**
     * removeCart
     *
     * @param  mixed $request
     * @return void
     */
   public function removeCart(Request $request)
{
    // Cari data berdasarkan ID yang dikirim
    $cart = Cart::where('id', $request->cart_id)->first();

    // Cek apakah data cart ditemukan
    if ($cart) {
        $cart->delete();
        return new CartResource(true, 'Success Remove Item Cart', null);
    }

    // Berikan respon error jika data tidak ditemukan (agar tidak crash)
    return response()->json([
        'success' => false,
        'message' => 'Data Cart tidak ditemukan atau sudah dihapus'
    ], 404);
}

}