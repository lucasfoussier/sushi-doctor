<?php

namespace App\EventSubscriber;

use DateTime;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class AuthenticationSuccessListener implements EventSubscriberInterface
{
    public function __construct(
        private RequestStack $requestStack,
    ) {}

    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $cookie = Cookie::create('REFRESH_TOKEN')
            ->withValue($data['refresh_token'])
            ->withExpires(new DateTime('+1 year')) // TODO : import param
            ->withPath('/api') // TODO : Import param
            ->withSecure(false) // TODO : Import param
            ->withHttpOnly(true)
        ;
        $event->getResponse()->headers->setCookie($cookie);
        unset($data['refresh_token']);
        $data = [
            'code' => 200,
            'message' => 'OK'
        ]; // TODO : create generic response
        $event->setData($data);
        $event->stopPropagation();
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::AUTHENTICATION_SUCCESS => [
                ['onAuthenticationSuccessResponse', 0]
            ],
        ];
    }
}
