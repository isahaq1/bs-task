<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class ApiBaseController extends Controller
{
    //Success Response

    public function sendSuccess($message, $status = 200, $data = null)
    {
        return response()->json(['message' => $message, 'data' => $data], $status);
    }

    //Errore Response
    public function sendError($message, $code = 404, $errors = null)
    {
        throw new HttpResponseException(response()->json(['message' => $message, 'errors' => $errors], $code));
    }
}
