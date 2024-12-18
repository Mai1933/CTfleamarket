<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RegisterResponse implements Responsable
{
    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toResponse($request)
    {
        return Redirect::to('/mypage/profile');
    }
}