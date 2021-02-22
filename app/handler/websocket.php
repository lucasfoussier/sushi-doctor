<?php

use Bref\Context\Context;
use Bref\Event\ApiGateway\WebsocketEvent;
use Bref\Event\ApiGateway\WebsocketHandler;
use Bref\Event\Http\HttpResponse;
use Bref\Websocket\SimpleWebsocketClient;
use AsyncAws\DynamoDb\Input\PutItemInput;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\DynamoDbClient;

require __DIR__ . '/../vendor/autoload.php';

class MyHandler extends WebsocketHandler
{

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
                    'TableName' => 'connectionPool',
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
                         'TableName' => 'connectionPool',
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
