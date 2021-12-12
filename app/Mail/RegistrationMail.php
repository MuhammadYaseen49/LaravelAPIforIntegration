<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $url;
    public function __construct($url)
    {
        $this->url = $url;
    }
   
    public function build()
    {
        return $this->from('yaseen49@gmail.com', 'M Yaseen')->subject('Account Registration Request Received')->view('registration');
    }
}
