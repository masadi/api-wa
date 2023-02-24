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
    protected $signature = 'kirim:wa {no} {text}';

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
        $url   = 'http://api.mas-adi.net/api/v1/messages'; // URL API
        $token = 'dk_b86ca5c34b3644838a59d980e50c8a5a';
        $data_post = [
            "recipient_type" => "individual",
            "to" => $this->argument('no'),
            "type" => "text",
            "text" => [
                "body" => $this->argument('text')
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
