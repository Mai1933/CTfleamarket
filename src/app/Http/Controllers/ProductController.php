<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function list()
    {
        $items = Item::all();
        return view('list',compact('items'));
    }

    public function detail($item_id)
    {
        $item = Item::find($item_id);
        $categories = Category::all();
        return view('detail',compact('item','categories'));
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
