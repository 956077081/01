<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;

class   DefaultController extends Controller
{
    public function index()
    {

        return view('admin/default/index');
    }
}