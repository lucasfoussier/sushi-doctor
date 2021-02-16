<?php
namespace App\EventSubscriber;

use DateTime;
use Exception;
use App\ValueObject\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener implements EventSubscriberInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private int $jwtCookieRefreshTtl,
        private string $jwtCookiePath,
        private bool $jwtCookieSecure,
    ) {}

    /**
     * @param AuthenticationSuccessEvent $event
     * @throws Exception
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $dateEndCookie = new DateTime('+' . (string) $this->jwtCookieRefreshTtl . ' seconds');
        $cookie = Cookie::create('REFRESH_TOKEN')
            ->withValue($data['refresh_token'])
            ->withExpires($dateEndCookie)
            ->withPath($this->jwtCookiePath)
            ->withSecure($this->jwtCookieSecure)
            ->withHttpOnly(true)
        ;
        $event->getResponse()->headers->setCookie($cookie);
        $response = new Response();
        $event->setData($response->toArray());
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
