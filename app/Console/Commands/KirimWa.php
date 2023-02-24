<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class KirimWa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kirim:wa {no} {text} {is_group} {nama}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $url   = config('wa.api_url');
        $token = config('wa.api_key');
        $data_post = [
            "recipient_type" => ($this->argument('is_group')) ? "group" : "individual",
            "to" => $this->argument('no'),
            "type" => "text",
            "text" => [
                "body" => 'Halo '.$this->argument('nama').'. Ini adalah jawaban otomatis '.$this->argument('text')
            ]
        ];
        $response = Http::withToken($token)->post($url, $data_post);
        /*$response = Http::withHeaders([
            'Authorization' => 'Bearer a38b622c-55c8-4620-a1ad-8f70798756c7',
        ])->post($url, $data_post);*/
        $this->info($response->status());
        dump($response);
        dd($response->json());
    }
}
