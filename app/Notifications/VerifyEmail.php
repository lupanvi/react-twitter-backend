<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

class VerifyEmail extends \Illuminate\Auth\Notifications\VerifyEmail implements ShouldQueue
{  

    use Queueable;

    /**
     * Change the url notification 
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {       

        $hash = Crypt::encrypt($notifiable->getKey());

        return config('frontend.email_verify_url') . $hash;
        
    }
}
