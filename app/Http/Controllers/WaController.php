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
        if (request()->isMethod('post')) {
            $rawdata = file_get_contents('php://input');
            $json = json_decode($rawdata, true);
            /*Storage::disk('public')->put('test.json', json_encode($json));
            Storage::disk('public')->put('sender.txt', $json['sender']);
            Storage::disk('public')->put('sender_phone.txt', $json['sender_phone']);
            Storage::disk('public')->put('chat.txt', $json['chat']);
            Storage::disk('public')->put('sender_push_name.txt', $json['sender_push_name']);
            Storage::disk('public')->put('message_id.txt', $json['message_id']);
            Storage::disk('public')->put('sender.txt', $json['sender']);
            Storage::disk('public')->put('sender.txt', $json['sender']);*/
            $type = 'text';
            if(strtolower($json['message_text']) == 'halo'){
                $recipient_type = ($json['is_group']) ? 'group' : 'individual';
                $to = ($json['is_group']) ? $json['chat'] : $json['sender_phone'];
                $data_post = $this->interactive($recipient_type, $to);
            } else {
                $data_post = [
                    'recipient_type' => ($json['is_group']) ? 'group' : 'individual',
                    'to' => ($json['is_group']) ? $json['chat'] : $json['sender_phone'],
                    'type' => 'text',
                    'text' => [
                        'body' => 'Halo '.$json['sender_push_name'].'. Ini adalah jawaban otomatis'
                    ]
                ];
            }
            $response = Http::withToken($this->api_key)->post($this->api_url, $data_post);
            /*$blok = ['6285231444789', '6285231548456'];
            if(!in_array($json['sender_phone'], $blok)){
                
            }*/
        } else {
            $data = [
                'test' => json_decode(Storage::disk('public')->get('test.json'))
            ];
            return response()->json($data);
        }
    }
    private function interactive($recipient_type, $to){
        $data_post = [
            'recipient_type' => $recipient_type, 
            'to' => $to, 
            'type' => 'interactive', 
            'interactive' => [
                'type' => 'list', 
                'header' => [
                    'text' => 'Ini adalah header' 
                ], 
                'body' => [
                    'text' => 'Silahkan lengkapi alamat Anda terlebih dahulu' 
                ], 
                'footer' => [
                    'text' => 'Pilih Provinsi' 
                ], 
                'action' => [
                    'button' => 'Provinsi', 
                    'sections' => [
                        [
                            'title' => 'Section 1', 
                            'rows' => [
                                [
                                    'id' => 'section.1.1', 
                                    'title' => 'Red', 
                                    'description' => 'Deskripsi warna' 
                                ], 
                                [
                                    'id' => 'section.1.2', 
                                    'title' => 'Blue', 
                                    'description' => 'Deskripsi warna' 
                                ],
                            ],
                        ], 
                        [
                            'title' => 'Section 2', 
                            'rows' => [
                                [
                                    'id' => 'section.2', 
                                    'title' => 'Green', 
                                    'description' => 'Deskripsi warna' 
                                ], 
                            ],
                        ],
                    ],
                ],
            ],
        ];
        return $data_post;
    }
}
