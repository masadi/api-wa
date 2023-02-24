<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WaController extends Controller
{
    public function index(){
        $data = [];
        return response()->json($data);
    }
}
