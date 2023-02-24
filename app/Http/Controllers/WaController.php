<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Storage;
use Artisan;

class WaController extends Controller
{
    public function index(){
        $rawdata = file_get_contents("php://input");
		$json = json_decode($rawdata, true);
        Storage::disk('public')->put('test.json', json_encode($json));
        Storage::disk('public')->put('sender.txt', $json['sender']);
        Storage::disk('public')->put('sender_phone.txt', $json['sender_phone']);
        Storage::disk('public')->put('chat.txt', $json['chat']);
        Storage::disk('public')->put('sender_push_name.txt', $json['sender_push_name']);
        Storage::disk('public')->put('message_id.txt', $json['message_id']);
        Storage::disk('public')->put('sender.txt', $json['sender']);
        Storage::disk('public')->put('sender.txt', $json['sender']);
        if(!$json['is_from_me']){

        }
        Artisan::call('kirim:wa', [
            'no' => ($json['is_group']) ? $json['chat'] : $json['sender_phone'],
            'text' => 'ini reply text',
            'is_group' => $json['is_group'],
            'nama' => $json['sender_push_name'],
        ]);
        $data = [];
        return response()->json($data);
    }
}
