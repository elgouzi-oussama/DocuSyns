<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RapportController extends Controller
{
    public function index()
    {
        return view('user.rapports.index');
    }
}
