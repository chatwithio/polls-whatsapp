<?php

namespace App\Controller;

use Symfony\Component\Config\Definition\Exception\Exception;
use App\Message\WhatsappNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Service\WhatsappService;

class HookController extends AbstractController
{
    #[Route('/hook-endpoint', name: 'hook_endpoint')]
    // POST
    public function whatsappHook(MessageBusInterface $bus, Request $request): Response
    {

        /*
         * Json:
         *
         * {
                "contacts": [
                    {
                        "profile": {
                            "name": "Ward"
                        },
                        "wa_id": "34697110110"
                    }
                ],
                "messages": [
                    {
                        "from": "34697110110",
                        "id": "ABGGNGIoFGQvAgo-sAr3kcI5DI30",
                        "text": {
                            "body": "Test from ward"
                        },
                        "timestamp": "1640174341",
                        "type": "text"
                    }
                ]
            }
         *
         *
         *
         */

        //dd($WhatsappService->sendWhatsAppText("34697110110","Hi there"));

        $bus->dispatch(new WhatsappNotification('Whatsapp me!'));

        return $this->json([
            'message' => 'Message sent!',
        ]);
    }

     /*
     * This is called form out own server
     * FORMAT: 
     *  
     * {
            "number":"34697110110"
        }
     */

    #[Route('/chatwith-endpoint', name: 'chatwith_endpoint')]
    // POST
    public function index(
        WhatsappService $whatsappService,
        Request $request,
        //ManagerRegistry $doctrine,
        LoggerInterface $logger): Response
    {
        $content = $request->getContent();
        $json = json_decode($content); //decode JSON and obtain data
        $status = "KO";
        $message = " ";
        $messageType = "";

        if (!is_numeric($json->number)) {
            $message = 'This is not a number';
        }
    
        else{
            try{
                $whatsappService->sendWhatsApp(
                    $json->number, //Number
                    [], //Placeholders
                    'poll', //template
                    'en', //language
                    'f6baa15e_fb52_4d4f_a5a0_cde307dc3a85');

                $status = "OK";
            }
            catch(\Exception $exception){
                $logger->error($exception->getMessage());
            }

        }

        return $this->json([
            'status' => $status,
            'message' => $message
        ]);
    }
}
