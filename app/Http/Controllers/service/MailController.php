<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    static function sendmail($vista, $username, $email, $data=[], $asunto)
    {
         Mail::send($vista, $data,function($message) use ($email, $username, $asunto)
        {
            $message->to($email)->subject($asunto.' ' .$username);
            $message->from('b.hamidou@badrweb.es', 'Jawalry');
        });

        return response()->json(["enviado" => true],200);
    }
}
