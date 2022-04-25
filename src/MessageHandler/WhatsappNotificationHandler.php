<?php

// src/MessageHandler/SmsNotificationHandler.php
namespace App\MessageHandler;

use App\Message\WhatsappNotification;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\WhatsappService;

#[AsMessageHandler]
class WhatsappNotificationHandler
{
    private $logger;

    private $em;

    private $whatsappService;
    private $doctrine;

    private $textType;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $em, WhatsappService $whatsappService, EntityManagerInterface $doctrine)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->whatsappService = $whatsappService;
        $this->doctrine = $doctrine;
    }

    public function __invoke(WhatsappNotification $message)
    {
        $content = $message->getContent();
        $jsonDecodedMessage = json_decode($content);

        $this->logger->info("Message sent!");
        
    }
}
