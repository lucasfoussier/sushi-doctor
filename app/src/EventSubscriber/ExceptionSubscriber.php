<?php
namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use App\ValueObject\Response;
use App\Service\ResponseService;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ExceptionSubscriber implements EventSubscriberInterface
{

    /**
     * ExceptionSubscriber constructor.
     * @param LoggerInterface $logger
     * @param ResponseService $responseService
     * @param string $appEnv
     */
    public function __construct(
        private LoggerInterface $logger,
        private ResponseService $responseService,
        private string $appEnv
    ) {}

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['handleException', 0],
            ],
        ];
    }

    /**
     * @param ExceptionEvent $event
     */
    public function handleException(ExceptionEvent $event)
    {
        if ('dev' === $this->appEnv) {
            return;
        }
        $exception = $event->getThrowable();
        if (!$exception instanceof AccessDeniedHttpException) {
            $this->logger->error(
                $exception->getMessage(),
                [
                    'code' => $exception->getCode(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ]
            );
        }
        if(method_exists($exception,'getStatusCode')){
            $code = $exception->getStatusCode();
            if(is_null($code)){
                $code = Response::HTTP_INTERNAL_SERVER_ERROR;
            }
        } else {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        if(isset(Response::ALLOWED_RESPONSE_CODE[$code])){
            $message = Response::ALLOWED_RESPONSE_CODE[$code];
            $response = $this->responseService->getError($code, $message);
            $event->setResponse($response);
        }
    }
}
