<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookViewController extends Controller
{
    public function index()
    {
        return view('books.index');
    }
}
