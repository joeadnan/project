<?php
namespace App\Http\Controllers\Api\Customer;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\CustomerResource;
use Illuminate\Support\Facades\Validator;
class RegisterController extends Controller
{

    public function register(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email|unique:customers',
            'password'  => 'required|min:8|confirmed', // butuh input password_confirmation
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 2. Simpan Customer Baru
        $customer = Customer::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password), // Password di-hash
        ]);

        if($customer) {
            return response()->json([
                'success' => true,
                'user'    => $customer,
            ], 201);
        }

        return response()->json([
            'success' => false,
        ], 409);
    }
    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:customers',
            'password' => 'required|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        //create customer
        $customer = Customer::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password)
        ]);
        if($customer) {
            //return with Api Resource
            return new CustomerResource(true, 'Register Customer
            Berhasil', $customer);
        }
        //return failed with Api Resource
        return new CustomerResource(false, 'Register Customer Gagal!',null);
    }}