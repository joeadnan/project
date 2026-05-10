<?php

namespace App\Http\Controllers\Api\Admin;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::when(request()->q, function($categories, $q){
            return $categories->where('name', 'LIKE', $q . "%");
        })->latest()->paginate(5);
        return new CategoryResource(true, 'List Data Kategori', $categories );
    }
    //store a newly created resource in storage
    //@param \Illuminate\Http\Request $request
    //@return \Illuminate\Http\Response
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|unique:categories',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //upload image
        $image = $request->file('image');
        $image->storeAs('public/categories', $image->hashName());
        //create category
        $category = Category::create([
            'image' => $image->hashName(),
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
        ]);
        if($category){
            return new CategoryResource(true, 'Data Kategori Berhasil Disimpan!', $category);
        }
        return new CategoryResource(false, 'Data Kategori Gagal Disimpan!', null);
    }
    public function show($id){
        $category = Category::whereId($id)->first();
        if($category){
            //return success with Api Resource
            return new CategoryResource(true, 'Data Kategori Ditemukan!', $category);
        }
        //return failed with Api Resource
        return new CategoryResource(false, 'Data Kategori Tidak Ditemukan!', null);
    }
    public function update(Request $request, Category $category){
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:categories,name,'.$category->id,
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        // check image update
        if($request->hasFile('image')){
            //remove old image
            Storage::disk('local')->delete('public/categories/'.$category->image);
            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/categories', $image->hashName());
            //update category with new image
            $category->update([
                'image' => $image->hashName(),
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-'),
            ]);
        } else {
            //update category without image
            $category->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-'),
            ]);
        }
        if($category){
            //return success with Api Resource
            return new CategoryResource(true, 'Data Kategori Berhasil Diupdate!', $category);
        }
        //return failed with Api Resource
        return new CategoryResource(false, 'Data Kategori Gagal Diupdate!', null);
    }
    public function destroy(Category $category){
        //remove old image
        Storage::disk('local')->delete('public/categories/'.basename($category->image));
        if($category->delete()){
            //return success with Api Resource
            return new CategoryResource(true, 'Data Kategori Berhasil Dihapus!', null);
        }
        //return failed with Api Resource
        return new CategoryResource(false, 'Data Kategori Gagal Dihapus!', null);
    }
    }
