<?php
namespace App\Service;

use Exception;
use App\ValueObject\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ResponseService
{

    public function __construct(
        private SerializerInterface $serializer,
    ) {}

    /**
     * @param object $data
     * @param string|null $message
     * @param array $headers
     * @return JsonResponse
     * @throws Exception
     */
    public function getResponse(object $data, string $message = null, array $headers = []): JsonResponse
    {
        $response = new Response();
        $response->setData($data);
        $response->setCode(Response::HTTP_OK);
        if(!is_null($message)){
            $response->setMessage($message);
        }
        return new JsonResponse($this->serializer->serialize($response, 'json',  ['groups' => 'response']), $response->getCode(), $headers, true);
    }


    public function getError(int $code, string $message, array $headers = []): JsonResponse
    {
        $response = new Response();
        $response->setMessage($message);
        $response->setCode($code);
        return new JsonResponse($this->serializer->serialize($response, 'json',  ['groups' => 'error']), $response->getCode(), $headers, true);
    }

}
