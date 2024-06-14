<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private $api_key = 'b50d549fd76b24145c412e6765dd547b';
    private $api_key_secret = '6cad7cf32f3bd4ead9437fe4c4e1222f';

    public function sendTemplateA($to_email, $to_name, $subject, $content)
    {
        $this->sendEmail($to_email, $to_name, $subject, $content,6056140 );
    }

    public function sendTemplateB($to_email, $to_name, $subject, $content)
    {
        $this->sendEmail($to_email, $to_name, $subject, $content,6056182 ); 
    }

    private function sendEmail($to_email, $to_name, $subject, $content, $templateId)
    {
        $mj = new Client($this->api_key, $this->api_key_secret, true, ['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "flavie.c@sensations-coaching.com",
                        'Name' => "Flavie de Sensations Coaching"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => $templateId,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}
