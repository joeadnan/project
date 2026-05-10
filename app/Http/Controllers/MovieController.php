<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public $movies;

    public function __construct()
    {
        $this->movies = [];
        for ($i = 0; $i < 10; $i++) {
            $this->movies[] = [
                'title'  => 'Movie Controller' . $i,
                'year'   => '2020',
                'gender' => 'Action',
            ];
        }
    }

    public function index(){
        $movie = $this->movies;
          return view('movies.index',compact('movie'));
    }

    public function show($id){
        $movie = $this->movies[$id];
        // return view('movies.show',['movie'=>$movie]);
        return view('movies.show',compact('movie'));
    }

    //     public function home(){
    //     // $movie = $this->movies[$id];
    //     // return view('movies.show',['movie'=>$movie]);
    //     return view('movies.home');
    // }

    // ✅ PINDAHKAN KE SINI
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'year' => 'required|string',
            'gender' => 'required|string',
        ]);

        $this->movies[] = $validated;

        return response()->json($validated, 201);
    }
    public function update(Request $request,$id){
        // $this ->movies[$id]['title']  = request('title');
        // $this ->movies[$id]['year']   = request('year');
        // $this ->movies[$id]['gender'] = request('gender');

        // return $this ->movies[$id];
        return $request->all();
    }
    public function destroy($id){
        unset($this->movies[$id]);
        return $this ->movies;
    }
    public function middleware(){
        // return [
        //     'IsAuth',
        //     new middleware('IsMember', only: ['show']),
        // ];
    }
}