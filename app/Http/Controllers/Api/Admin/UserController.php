<?php

namespace App\Http\Controllers\Api\Admin;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    /**
     * Display a listing of the Resource
     * @return \Illuminate\Http\Response
     */
    public function index(){
        //get users
        $users = User::when(request()->q, function($users){
            $users =  $users->where('name', 'like', '%' . request()->q . '%');
        })->latest()->paginate(5);
        //return with Api Resource
        return new UserResource(true, 'List Data Users', $users);
    } 
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|unique:Users',
            'password' => 'required|confirmed'
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        //create user
        $user = User::created([
            'name'  => $request->name,
            'email' => $request->email,
            'password'  => bcrypt($request->password)
        ]);
        if($user){
            //return succes with Api Resource
            return new UserResource(false, 'Data User Gagal Disimpan', null);
        }
    }
    /**
     * Display the specified resource
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::whereId($id)->first();
        if($user){
            //return succes With Api Resource
            return new UserResource(true, 'Detail Data User!', $user);
        }
        //return failed with Api Resource
        return new UserResource(false, 'Detail Data User Tidak Ditemukan', null);
    }
    /**
     * update the specified resource in storage
     * @param \Illuminate\Http\request $ request
     * @param int $id
     * @return \Illuminate\Http\Resource
     */
    public function update(Request $request, User $user){
        $validator = Validator::make($request->all(),[
            'name'  => 'required',
            'email' => 'required|unique::users,email,'.$user->id,
            'password' => 'confirmed'
        ]);
        if($validator->fails()){
            return response()->json($validator->error(), 422);
        }
        if($request->password == ""){
            //update user without password
            $user->update([
                'name'  =>$request->name,
                'email' =>$request->email,
            ]);
        }
        //update user with new password
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);
        if($user){
            //return failed with Api Resource
            return new UserResource(false,'Data user Gagal Diupdate', null);
        }
        /**
         * remove the specified resource from storage
         * @param int id
         * @return \Illuminate\Http\Response
         */
    }
     public function destroy(User $user){
        if($user->delete()){
            //return succes with Api Resource
            return new UserResource(true, 'Data User Berhasil Dihapis', null);
        }
        return new UserResource(false, 'Data User Gagal Dihapus', null);
     }
}
