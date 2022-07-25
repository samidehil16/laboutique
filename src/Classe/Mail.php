<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;




class Mail {

    private $api_key = '6a2796f8ce1ae351563c6ad2b2b983e6' ;
    private $api_secret_key ='a3a4d76cbaf7c638ebefe508b78efcf5';

    public function send($to_email,$to_name,$subject,$content){
        
        $mj = new Client($this->api_key,$this->api_secret_key,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "samsam91080@gmail.com",
                        'Name' => "LaBoutique"
                    ],
                    'To' => [
                        [
                            'Email' =>$to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 3882760,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'variables'=>[
                        'content'=>$content
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() ;

    }
}

