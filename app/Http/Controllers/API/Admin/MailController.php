<?php

namespace App\Http\Controllers\API\Admin;

use App\Mail\NotifyMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendMail()
    {

        //Mail::to('fake@mail.com')->send(new NotifyMail()));
    }
}
