<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\Kategori;
use App\Models\Pesan;
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
            $file = str_replace(' ', '-', strtolower($json['message_text']));
            /*Storage::disk('public')->put($file.'.json', json_encode($json));
            Storage::disk('public')->put('sender.txt', $json['sender']);
            Storage::disk('public')->put('sender_phone.txt', $json['sender_phone']);
            Storage::disk('public')->put('chat.txt', $json['chat']);
            Storage::disk('public')->put('sender_push_name.txt', $json['sender_push_name']);
            Storage::disk('public')->put('message_id.txt', $json['message_id']);
            Storage::disk('public')->put('sender.txt', $json['sender']);
            Storage::disk('public')->put('sender.txt', $json['sender']);*/
            $recipient_type = ($json['is_group']) ? 'group' : 'individual';
            $to = ($json['is_group']) ? $json['chat'] : $json['sender_phone'];
            if($json['message_text_id']){
                if(Str::contains($json['message_text_id'], 'kategori')){
                    $data_post = $this->button_pesan($json['message_text_id'], $recipient_type, $to, $json['sender_push_name'], $json['message_text']);
                } elseif(Str::contains($json['message_text_id'], 'induk')){
                    $data_post = $this->button_kategori($json['message_text_id'], $recipient_type, $to, $json['sender_push_name'], $json['message_text']);
                } elseif(Str::contains($json['message_text_id'], 'pesan')){
                    $pesan_id = str_replace('pesan-', '', $json['message_text_id']);
                    //$data_post = $this->kirim_pesan($json['message_text_id'], $recipient_type, $to, $json['sender_push_name']);
                    $data_post = [
                        'recipient_type' => ($json['is_group']) ? 'group' : 'individual',
                        'to' => ($json['is_group']) ? $json['chat'] : $json['sender_phone'],
                        'type' => 'text',
                        'text' => [
                            'body' => $this->kirim_pesan($pesan_id)
                        ]
                    ];
                } else {
                    $data_post = $this->button($recipient_type, $to, $json['sender_push_name']);
                }
            } else {
                if(strtolower($json['message_text']) == 'list'){
                    $data_post = $this->interactive($recipient_type, $to, $json['sender_push_name']);
                } elseif(strtolower($json['message_text']) == 'Menu Awal'){
                    //echo $int;
                    $data_post = $this->button($recipient_type, $to, $json['sender_push_name']);
                } elseif(Str::contains($json['message_text'], 'NPSN')){
                    $int = filter_var($json['message_text'], FILTER_SANITIZE_NUMBER_INT);
                    Storage::disk('public')->put('npsn.txt', $int);
                    $data_post = $this->button($recipient_type, $to, $json['sender_push_name']);
                } else {
                    $data_post = [
                        'recipient_type' => ($json['is_group']) ? 'group' : 'individual',
                        'to' => ($json['is_group']) ? $json['chat'] : $json['sender_phone'],
                        'type' => 'text',
                        'text' => [
                            'body' => 'Halo '.$json['sender_push_name'].'. Untuk proses tracking Bantuan, silahkan isi Data dibawah ini:'."\n"."\n".'Nama                	:'."\n".'Nama Sekolah        :'."\n".'NPSN               	:'
                        ]
                    ];
                }
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
    private function kirim_pesan($pesan_id){
        $data = Pesan::find($pesan_id);
        return $data->deskripsi;
    }
    private function button_pesan($message_text_id, $recipient_type, $to, $nama, $judul){
        $message_text_id = str_replace('kategori-', '', $message_text_id);
        $pesan = Pesan::with(['kategori'])->where('kategori_id', $message_text_id)->orderBy('id')->get();
        $buttons = [];
        foreach($pesan as $p){
            $buttons[] = [
                'type' => 'reply', 
                'reply' => [
                    'id' => 'pesan-'.$p->id, 
                    'title' => $p->judul, 
                ],
            ];
        }
        if($kategori->induk){
            $merger = [
                [
                    'type' => 'reply', 
                    'reply' => [
                        'id' => 'induk-'.$message_text_id, 
                        'title' => 'Menu Sebelumnya', 
                    ],
                ],
                [
                    'type' => 'reply', 
                    'reply' => [
                        'id' => 'back', 
                        'title' => 'Menu Awal', 
                    ],
                ],
            ];
        } else {
            $merger = [
                [
                    'type' => 'reply', 
                    'reply' => [
                        'id' => 'back', 
                        'title' => 'Menu Awal', 
                    ],
                ],
            ];
        }
        $buttons = array_merge($buttons, $merger);
        $data_post = [
            'recipient_type' => $recipient_type, 
            'to' => $to, 
            'type' => 'interactive', 
            'interactive' => [
                'type' => 'button', 
                'header' => [
                    'text' => 'Pertanyaan Seputar '.$judul
                ], 
                'body' => [
                    'text' => 'Silahkan pilih Menu dibawah kategori '.$judul, 
                ], 
                'footer' => [
                    'text' => 'Pilih Menu' 
                ], 
                'action' => [
                    'buttons' => $buttons,
                ],
            ],
        ]; 
        return $data_post;
    }
    private function button_kategori($message_text_id, $recipient_type, $to, $nama){
        $message_text_id = str_replace('induk-', '', $message_text_id);
        $kategori = Kategori::where('induk', $kategori_id)->orderBy('id')->get();
        $buttons = [];
        foreach($kategori as $k){
            $buttons[] = [
                'type' => 'reply', 
                'reply' => [
                    'id' => 'kategori-'.$k->id, 
                    'title' => $k->judul, 
                ],
            ];
        }
        $data_post = [
            'recipient_type' => $recipient_type, 
            'to' => $to, 
            'type' => 'interactive', 
            'interactive' => [
                'type' => 'button', 
                'header' => [
                    'text' => 'Halo Bapak/Ibu '.$nama
                ], 
                'body' => [
                    'text' => 'Silahkan pilih Menu Sub Kategori dibawah kategori '.$judul, 
                ], 
                'footer' => [
                    'text' => 'Pilih Menu' 
                ], 
                'action' => [
                    'buttons' => $buttons,
                ],
            ],
        ]; 
        return $data_post;
    }
    private function button($recipient_type, $to, $nama){
        $kategori = Kategori::orderBy('id')->get();
        $buttons = [];
        foreach($kategori as $k){
            $buttons[] = [
                'type' => 'reply', 
                'reply' => [
                    'id' => 'kategori-'.$k->id, 
                    'title' => $k->judul, 
                ],
            ];
        }
        Storage::disk('public')->put('isi_button.json', json_encode($buttons));
        $data_post = [
            'recipient_type' => $recipient_type, 
            'to' => $to, 
            'type' => 'interactive', 
            'interactive' => [
                'type' => 'button', 
                'header' => [
                    'text' => 'Halo Bapak/Ibu '.$nama
                ], 
                'body' => [
                    'text' => 'Selamat Datang di Pusat Bantuan Aplikasi e-Rapor SMK. Silahkan pilih Menu dibawah ini sesuai permasalahan yang Anda temukan!' 
                ], 
                'footer' => [
                    'text' => 'Pilih Menu' 
                ], 
                'action' => [
                    'buttons' => $buttons,
                ],
            ],
        ]; 
        return $data_post;
    }
    private function list_text($message_text_id){
        $collection = collect([
            ['message_text_id' => 1, 'title' => 'Merah', 'description' => 'Deskripsi warna Merah'],
            ['message_text_id' => 2, 'title' => 'Putih', 'description' => 'Deskripsi warna Putih'],
            ['message_text_id' => 3, 'title' => 'Hijau', 'description' => 'Deskripsi warna Hijau'],
            ['message_text_id' => 4, 'title' => 'Hitam', 'description' => 'Deskripsi warna Hitam'],
            ['message_text_id' => 5, 'title' => 'Hitam', 'description' => 'Deskripsi warna Hitam'],
            ['message_text_id' => 6, 'title' => 'Hitam', 'description' => 'Deskripsi warna Hitam'],
        ]);
        return $collection->firstWhere('message_text_id', $message_text_id);
        //return $collection;
    }
    private function interactive($recipient_type, $to, $nama){
        $data_post = [
            'recipient_type' => $recipient_type, 
            'to' => $to, 
            'type' => 'interactive', 
            'interactive' => [
                'type' => 'list', 
                'header' => [
                    'text' => 'Halo Bapak/Ibu '.$nama 
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
