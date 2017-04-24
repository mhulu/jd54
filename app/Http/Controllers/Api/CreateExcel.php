<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Excel;
use File;
use Response;
use App\User;
use  Star\utils\StarJson;

class CreateExcel extends Controller
{
    public function export()
    {
        $user = new User;
        $data = $user->all()->get()->only(['id', 'mobile', 'name', 'created_at']);
        dd($data);
        $export = Excel::create('内部人员列表', function($excel) use ($data)
        {
            
            $excel->sheet('内部人员列表', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->store('xls', false ,true);
        
        return StarJson::create(200, [
        'url' => url('storage/exports/'.$export['file'])
        ]);
        
    }
    
    public function download($filename)
    {
        $url = storage_path('exports/'. $filename);
        if (!File::exists($url)) {
            return StarJson::create(404);
        }
        $file = File::get($url);
        $type = File::mimeType($url);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }
}