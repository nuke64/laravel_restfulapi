<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected function error($msg)
    {
        return response()->json($this->addStatus(array(), 'failed', $msg), 404 );
    }

    protected function addStatus($data, $status, $msg = null)
    {
        return array_merge($data,
            [
                'status' => $status,
                'message' => $msg
            ]
        );
    }
}
