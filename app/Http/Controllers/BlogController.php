<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::user()->id;
        //::where( 'user_id', '=', $userId )
        //dd($Blog);

        $blogs = Blog::sortable()->where( 'user_id', '=', $userId )->paginate(5);

        return view('dashboard', [
            'blogs' => $blogs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('addblog');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = $request->only('title', 'description', 'publication_date' );
        $data['user_id'] = Auth::user()->id;        

        if( empty($data['user_id']) ){
            throw new \Exception("Problem with finding user");
        }

        $blog = Blog::create($data);
        if (empty($blog->id)) {
            throw new \Exception("I cant create blog");
        }

        return redirect()->route('dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        //
    }
}
