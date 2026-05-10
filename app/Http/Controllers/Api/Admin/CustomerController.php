<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource
     * @return \ILLuminate\Http\Resource
     */
   public function index()
{
    $customers = Customer::when(request()->q, function($query) {
        return $query->where('name', 'like', '%' . request()->q . '%');
    })->latest()->paginate(5);

    return new CustomerResource(true, 'List Data Customer', $customers);
    }
     public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:customers',
        ]);

        // 2. Simpan data
        $customer = Customer::create([
            'name'  => $request->name,
            'email' => $request->email,
            'password'  => bcrypt($request->password),
        ]);

        // 3. Return response
        return response()->json([
            'success' => true,
            'message' => 'Customer Berhasil Disimpan',
            'data'    => $customer
        ], 201);
    }
    public function show($id)
    {
    // Cari data customer berdasarkan ID
    $customer = Customer::find($id);

    if ($customer) {
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Customer',
            'data'    => $customer
        ]);
    }

    // Jika data tidak ditemukan
    return response()->json([
        'success' => false,
        'message' => 'Data Customer Tidak Ditemukan',
        ], 404);
    }
    public function update(Request $request, $id)
{
    // 1. Cari data customer
    $customer = Customer::find($id);

    if (!$customer) {
        return response()->json([
            'success' => false,
            'message' => 'Data Customer Tidak Ditemukan',
        ], 404);
    }

    // 2. Validasi input
    $request->validate([
        'name'  => 'required',
        'email' => 'required|email|unique:customers,email,' . $customer->id,
    ]);

    // 3. Update data
    $customer->update([
        'name'     => $request->name,
        'email'    => $request->email,
        // Update password hanya jika diisi (opsional)
        'password' => $request->password ? bcrypt($request->password) : $customer->password,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Data Customer Berhasil Diupdate',
        'data'    => $customer
        ]);
    }
    public function destroy($id)
{
    // 1. Cari data customer
    $customer = Customer::find($id);

    if (!$customer) {
        return response()->json([
            'success' => false,
            'message' => 'Data Customer Tidak Ditemukan',
        ], 404);
    }

    // 2. Hapus data
    $customer->delete();

    // 3. Return response sukses
    return response()->json([
        'success' => true,
        'message' => 'Data Customer Berhasil Dihapus'
     ]);
    }   
 }
