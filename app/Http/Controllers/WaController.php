<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Storage;

class WaController extends Controller
{
    public function index(){
        $rawdata = file_get_contents("php://input");
		$json = json_decode($rawdata, true);
        Storage::disk('public')->put('test.json', json_encode($json));
        Storage::disk('public')->put('nama.txt', $json['chat']);
        $data = [];
        return response()->json($data);
    }
}
