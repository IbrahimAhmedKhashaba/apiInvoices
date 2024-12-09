<?php

namespace App;

trait apiResponseTrait
{
    //
    public function apiResponse($data = null , $msg = '' , $code = ''){
        if($data){
            return response()->json([
                'data' => $data,
                'code' => $code,
                'msg' => $msg,
            ]);
        }
        return response()->json([
            'data' => null,
            'code' => $code,
            'msg' => $msg,
        ]);
    }
}
