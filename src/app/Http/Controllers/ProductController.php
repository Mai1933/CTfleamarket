<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function register()
    {
        return view('register');
    }

    public function login()
    {
        return view('login');
    }

    public function list()
    {
        return view('list');
    }

    public function detail()
    {
        return view('detail');
    }

    public function buy()
    {
        return view('buy');
    }

    public function address()
    {
        return view('address');
    }

    public function sell()
    {
        return view('sell');
    }

    public function profile()
    {
        return view('profile');
    }

    public function edit()
    {
        return view('edit');
    }
}
