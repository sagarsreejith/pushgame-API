<?php

namespace gleams\Http\Controllers;

use Illuminate\Http\Request;
use gleams\Http\Requests;
use gleams\Http\Controllers\Controller;

class AboutController extends Controller
{
    public function create()
    {
        return view('about.contact');
    }

    public function store(Request $request)
    {
		//$input = $request->all();
		$iphone_data = file_get_contents("php://input");
		$json = json_decode($iphone_data, true);
		//print_r($input);
		return $json;
    }
}
