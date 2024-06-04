<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private $api_key = 'c086157ae58ea2bca00fd960ccf91863';
    private $api_key_secret = '4745e1382c4c3e11e49388c8724fe8d1';
    
    public function send($to_email,$to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret,true,['version' => 'v3.1']);
        $body = [
          'Messages' => [
            [
              'From' => [
                #flavie.c@sensations-coaching.com
                'Email' => "apolyonar@gmail.com",
                'Name' => "Sensations"
              ],
              'To' => [
                [
                  'Email' => $to_email,
                  'Name' => $to_name
                ]
              ],
              'TemplateID' => 6007104,
              'TemplateLanguage' => true,
              'Subject' => $subject,
              'Variables' => [
                'content'=> $content,
            ]
            ]
            ]
          ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}