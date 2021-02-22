<?php

use Bref\Context\Context;
use Bref\Event\ApiGateway\WebsocketEvent;
use Bref\Event\ApiGateway\WebsocketHandler;
use Bref\Event\Http\HttpResponse;
use Bref\Websocket\SimpleWebsocketClient;
use AsyncAws\DynamoDb\Input\PutItemInput;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\DynamoDbClient;
use Bref\Symfony\Messenger\Service\Sqs\SqsConsumer;
use Symfony\Component\Dotenv\Dotenv;

//require __DIR__ . '/../vendor/autoload.php';
require dirname(__DIR__).'/vendor/autoload.php';

class MyHandler extends WebsocketHandler
{


    public function __construct()
    {
        if(!isset($_SERVER['APP_ENV'])){
            (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
        } else {
            if($_SERVER['APP_ENV'] !== 'prod') {
                (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
            }
        }

        if(isset($_SERVER['APP_STAGE'])){
            define('APP_STAGE', $_SERVER['APP_STAGE']);
        } else {
            throw new \Exception('APP_STAGE undefined');
        }

        if(isset($_SERVER['APP_NAME'])){
            define('APP_NAME', $_SERVER['APP_NAME']);
        } else {
            throw new \Exception('APP_NAME undefined');
        }

        define('DYNAMODB_TABLE_PREFIX', APP_NAME.'.'.APP_STAGE.'.');

        $kernel = new \App\Kernel($_SERVER['APP_ENV'], (bool)$_SERVER['APP_DEBUG']);
        $kernel->boot();

    }

    public function handleWebsocket(WebsocketEvent $event, Context $context): HttpResponse
    {
        $dynamoDb = new DynamoDbClient();

        switch ($event->getEventType()) {
            case 'CONNECT':
                $dynamoDb->putItem(new PutItemInput([
                    'TableName' => 'websocket.pool',
                    'Item' => [
                        'connectionId' => new AttributeValue(['S' => $event->getConnectionId()]),
                        'apiId' => new AttributeValue(['S' => $event->getApiId()]),
                        'region' => new AttributeValue(['S' => $event->getRegion()]),
                        'stage' => new AttributeValue(['S' => $event->getStage()]),
                    ],
                ]));

                return new HttpResponse('connect');

            case 'DISCONNECT':
                $dynamoDb->deleteItem([
                    'TableName' => 'websocket.pool',
                    'Key' => [
                        'connectionId' => [
                            'S' => $event->getConnectionId(),
                        ],
                    ]
                ]);

                return new HttpResponse('disconnect');

            default:
                if ($event->getBody() === 'ping') {
                    foreach ($dynamoDb->scan([
                         'TableName' => 'websocket.pool',
                    ])->getItems() as $item) {
                        $connectionId = $item['connectionId']->getS();
                        $client = SimpleWebsocketClient::create(
                            $item['apiId']->getS(),
                            $item['region']->getS(),
                            $item['stage']->getS()
                        );
                        $client->message($connectionId, 'pong');
                        $status = $client->status($connectionId);

                        echo json_encode($status->toArray());
                    }
                }

                return new HttpResponse('message');
        }
    }
}

return new MyHandler();
