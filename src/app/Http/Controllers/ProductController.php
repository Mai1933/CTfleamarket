<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

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

    public function buy($item_id)
    {
        $item = Item::find($item_id);
        return view('buy',compact('item'));
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

    protected $createNewUser;

    // public function __construct(CreateNewUser $createNewUser)
    // {
    //     $this->createNewUser = $createNewUser;
    // }

    // public function store(RegisterRequest $request) {
    //     $user = $this->createNewUser->create($request->validated());

    //     return response()->json($user); }
}
