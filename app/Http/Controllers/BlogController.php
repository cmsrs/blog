<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;

class BlogController extends Controller
{

    private $validationRules = [
        'title' => 'min:2|max:9200|required',
        'description' => 'min:2|max:9200',
        'publication_date' => 'required'    
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authUser = Auth::user();
        $blogs = Blog::sortable()->where( 'user_id', '=', $authUser->id )->orderBy('publication_date', 'desc')->paginate(5);
        //$blogs = Blog::sortable()->where( 'user_id', '=', $authUser->id )->paginate(5);

        return view('dashboard', [
            'blogs' => $blogs,
            'is_admin' => $authUser->is_admin
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('addblog');
    }

    /**
     * Import blogs from external API
     */
    public function import(Request $request)
    {
        (new Blog)->import();

        $request->session()->flash('status', 'The data has been successfully imported');    
        return redirect()->route('dashboard');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = $request->only('title', 'description', 'publication_date' );
        $validator = Validator::make($data, $this->validationRules);
        if ($validator->fails()) {
            $request->session()->flash('status', 'Fields: title and description should have a length between 2 and 9200 characters.');     
            return redirect()->route('dashboard.blog.create');
        }

        $data['user_id'] = Auth::user()->id;        

        if( empty($data['user_id']) ){
            throw new \Exception("Problem with finding user");
        }

        $blog = Blog::create($data);
        if (empty($blog->id)) {
            throw new \Exception("I cant create blog");
        }
    
        $request->session()->flash('status', 'Your blog has been successfully created');    
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
