<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Storage;

class WaController extends Controller
{
    public function index(){
        Storage::disk('public')->put('test.json', json_encode(request()->all()));
        $data = [];
        return response()->json($data);
    }
}
