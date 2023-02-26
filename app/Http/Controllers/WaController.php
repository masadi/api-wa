<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Storage;
use Artisan;

class WaController extends Controller
{
    public function __construct()
    {
        $this->api_url = config('wa.api_url');
        $this->api_key = config('wa.api_key');
    }
    public function index(){
        $rawdata = file_get_contents("php://input");
		$json = json_decode($rawdata, true);
        /*Storage::disk('public')->put('test.json', json_encode($json));
        Storage::disk('public')->put('sender.txt', $json['sender']);
        Storage::disk('public')->put('sender_phone.txt', $json['sender_phone']);
        Storage::disk('public')->put('chat.txt', $json['chat']);
        Storage::disk('public')->put('sender_push_name.txt', $json['sender_push_name']);
        Storage::disk('public')->put('message_id.txt', $json['message_id']);
        Storage::disk('public')->put('sender.txt', $json['sender']);
        Storage::disk('public')->put('sender.txt', $json['sender']);*/
        $blok = ['6285231444789', '6285231548456'];
        if(!in_array($json['sender_phone'], $blok)){
            /*Artisan::call('kirim:wa', [
                'no' => ($json['is_group']) ? $json['chat'] : $json['sender_phone'],
                'text' => 'ini reply text',
                'is_group' => $json['is_group'],
                'nama' => $json['sender_push_name'],
            ]);*/
            $data_post = [
                "recipient_type" => ($json['is_group']) ? "group" : "individual",
                "to" => ($json['is_group']) ? $json['chat'] : $json['sender_phone'],
                "type" => "text",
                "text" => [
                    "body" => 'Halo '.$json['sender_push_name'].'. Ini adalah jawaban otomatis'
                ]
            ];
            $response = Http::withToken($token)->post($url, $data_post);
        }
        $data = [];
        return response()->json($data);
    }
}
