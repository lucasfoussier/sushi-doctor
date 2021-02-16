<?php
namespace App\ValueObject;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;

class Response
{
    public const HTTP_OK = 200;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;
    public const HTTP_BAD_GATEWAY = 502;
    public const HTTP_SERVICE_UNAVAILABLE = 503;
    public const HTTP_GATEWAY_TIMEOUT = 504;

    public const ALLOWED_RESPONSE_CODE = [
        self::HTTP_OK => 'OK',
        self::HTTP_BAD_REQUEST => 'Bad Request',
        self::HTTP_UNAUTHORIZED => 'Unauthorized',
        self::HTTP_FORBIDDEN => 'Forbidden',
        self::HTTP_NOT_FOUND => 'Not found',
        self::HTTP_METHOD_NOT_ALLOWED => 'Method not allowed',
        self::HTTP_INTERNAL_SERVER_ERROR => 'Internal server error',
        self::HTTP_BAD_GATEWAY => 'Bad Gateway',
        self::HTTP_SERVICE_UNAVAILABLE => 'Service unavailable',
        self::HTTP_GATEWAY_TIMEOUT => 'Gateway timeout'
    ];

    /**
     * @Groups({"error", "response"})
     */
    protected int $code;

    /**
     * @Groups({"error", "response"})
     */
    protected string $message;

    /**
     * @Groups({"response"})
     */
    protected object $data;

    /**
     * Response constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->code = self::HTTP_OK;
        $this->message = self::ALLOWED_RESPONSE_CODE[self::HTTP_OK];
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     * @return Response
     * @throws Exception
     */
    public function setCode(int $code): self
    {
        if(!isset(self::ALLOWED_RESPONSE_CODE[$code])){
            throw new  Exception('Response code not allowed !');
        }
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return Response
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return object
     */
    public function getData(): object
    {
        return $this->data;
    }

    /**
     * @param object $data
     * @return Response
     */
    public function setData(object $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function toArray() {



        $array = [
            'code' => $this->code,
            'message' => $this->message,
        ];
        if(isset($this->data)){
            $array['data'] = $this->data;
        }
        return $array;
    }


}
