<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UploadFileController extends Controller
{
    public function uploadFile(Request $request)
    {
        $res = new \App\Libs\Result();
        try {
            if (!$request->hasFile('image') || !$request->file('image')->isValid()) {  throw new \Exception('Problème d\'upload image');  }
            $valid = Validator::make($request->all(), [   'image' => 'mimes:jpeg,png|max:3048']);
            if ($valid->fails()) { throw new \Exception($valid->errors()->all()[0]);   }
            $name = Str::random(9);
            $extension = $request->image->extension();
            $request->image->storeAs('/public', $name . "." . $extension);
            $url = Storage::url($name . "." . $extension);
            $res->success(['path' => $request->getSchemeAndHttpHost(). $url]);
        } catch (\Exception $e) {
            $res->fail($e->getMessage());
        }
        return response()->json($res);
    }
}
