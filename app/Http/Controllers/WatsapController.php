<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Storage;

class WatsapController extends Controller
{
    public function __construct()
    {
        $this->api_url = config('wa.watsap_api_url');
        $this->api_key = config('wa.watsap_api_key');
        $this->dashboard_url= config('wa.dashboard_url');;
    }
    public function index(){
        if (request()->isMethod('post')) {
            $rawdata = file_get_contents('php://input');
            $json = json_decode($rawdata, true);
            Storage::disk('public')->put('watsap_api.json', json_encode($json));
            $message = strtolower($json['message']);
            $from = strtolower($json['from']);
            $data = false;
            if($message === 'hai'){
                $data = $this->sayHello();
            } else if($message === 'gambar'){
                $data = $this->gambar();
            } else if($message === 'tes button'){
                $data = $this->button();
            } else if($message === 'lists msg'){
                $data = $this->lists();
            }
        } else {
            $data = ['api' => 'Unauthorized'];
        }
        return response()->json($data);
    }
    private function sayHello(){    
        return ["text" => 'Halloooo!'];
    }
    private function gambar(){
        return [
            'image' => ['url' => 'https://seeklogo.com/images/W/whatsapp-logo-A5A7F17DC1-seeklogo.com.png'],
            'caption' => 'Logo whatsapp!'
        ];
    }
    private function button(){
        $buttons = [
            ['buttonId' => 'id1', 'buttonText' => ['displayText' => 'BUTTON 1'], 'type' => 1], // button 1 // 
            ['buttonId' => 'id2', 'buttonText' => ['displayText' => 'BUTTON 2'], 'type' => 1], // button 2
            ['buttonId' => 'id3', 'buttonText' => ['displayText' => 'BUTTON 3'], 'type' => 1], // button 3
        ];
        $buttonMessage = [
            'text' => 'HOLA, INI ADALAH PESAN BUTTON', 
            'footer' => 'ini pesan footer', 
            'buttons' => $buttons,
            'headerType' => 1 
        ];
        return $buttonMessage;
    }
    private function lists(){
        $sections = [
            [ 
                "title" => "This is List menu",
                "rows" => [
                    ["title" => "List 1", "description" => "this is list one"],
                    ["title" => "List 2", "description" => "this is list two"],
                ], 
            ],
       ];
        $listMessage = [
            "text" => "This is a list",
            "title" => "Title Chat",
            "buttonText" => "Select what will you do?",
            "sections" => $sections
        ];
        return $listMessage;  
    }
    public function kirim(){
        $data = [
            'api_key' => $this->api_key,
            'sender' => '6285231444789',
            'number' => '6287864496339',
            'message' => 'Your message'
        ];
        $response = Http::withToken($this->api_key)->post($this->api_url.'send-message', $data);
        return response()->json($response->json());
    }
}
