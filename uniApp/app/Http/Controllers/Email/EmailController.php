<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function send(){

        Mail::send($view, ["code" => $code,"codeN" => $codeN, "expireTime" => $expireTime, "status" => $status, "email" => $email], function (Message $message) use ($email) {
            $message->to($email);
            $message->subject("Reiki Email");
        });
        if (Mail::failures()) {
            return ["code" => 0, "msg" => "warning"];
        } else {
            return ["code" => 1, "msg" => "success"];
        }
    }
}
