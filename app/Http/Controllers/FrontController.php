<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class FrontController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::All()->toArray();//->toArray();

        return view('front', [
            'blogs' => $blogs
        ]);

    }


}
