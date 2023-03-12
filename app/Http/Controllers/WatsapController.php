<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        } else {
            $data = [
                'api' => 'Unauthorized'
            ];
            return response()->json($data);
        }
    }
}
