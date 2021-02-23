<?php

use App\Kernel;
use Bref\Context\Context;
use Bref\Event\ApiGateway\WebsocketEvent;
use Bref\Event\ApiGateway\WebsocketHandler;
use Bref\Event\Http\HttpResponse;
use Bref\Websocket\SimpleWebsocketClient;
use AsyncAws\DynamoDb\DynamoDbClient;
use Symfony\Component\Dotenv\Dotenv;
use JLucki\ODM\Spark\Spark;
use App\Entity\WebsocketPool;

require dirname(__DIR__).'/vendor/autoload.php';

class MyHandler extends WebsocketHandler
{
    private Spark $spark;
    private string $region;

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
        $kernel = new Kernel($_SERVER['APP_ENV'], (bool)$_SERVER['APP_DEBUG']);
        $kernel->boot();
        /* @var $spark Spark*/
        $spark = $kernel->getContainer()->get(Spark::class);
        /* @var $region string */
        $region = $kernel->getContainer()->getParameter('aws.deployment.region');
        $this->spark = $spark;
        $this->region = $region;
    }

    public function handleWebsocket(WebsocketEvent $event, Context $context): HttpResponse
    {
        $dynamoDb = new DynamoDbClient();
        switch ($event->getEventType()) {
            case 'CONNECT':
                $websocketPool = new WebsocketPool();
                $websocketPool->setConnectionId($event->getConnectionId());
                $websocketPool->setApiId($event->getApiId());
                $websocketPool->setRegion($this->region);
                $websocketPool->setStage($event->getStage());
                $this->spark->putItem($websocketPool);
                return new HttpResponse('connect');
            case 'DISCONNECT':
                $currentPool = $this->spark->getItem(WebsocketPool::class, [
                    'connectionId' => $event->getConnectionId(),
                ]);
                $this->spark->deleteItem($currentPool);
                return new HttpResponse('disconnect');
            default:
                if ($event->getBody() === 'ping') {
                    $pools = $this->spark->scan(WebsocketPool::class);
                    /* @var $pool WebsocketPool */
                    foreach ($pools as $pool){
//                        $connectionId = $item['connectionId']->getS();
                        $connectionId = $pool->getConnectionId();
                        $client = SimpleWebsocketClient::create(
                            $pool->getApiId(),
                            $pool->getRegion(),
                            $pool->getStage()
                        );
                        $client->message($connectionId, 'pong');
                        $status = $client->status($connectionId);
                        echo json_encode($status->toArray());
                    }
//                    foreach ($dynamoDb->scan([
//                         'TableName' => 'websocket.pool',
//                    ])->getItems() as $item) {
//                        $connectionId = $item['connectionId']->getS();
//                        $client = SimpleWebsocketClient::create(
//                            $item['apiId']->getS(),
//                            $item['region']->getS(),
//                            $item['stage']->getS()
//                        );
//                        $client->message($connectionId, 'pong');
//                        $status = $client->status($connectionId);
//                        echo json_encode($status->toArray());
//                    }
                }

                return new HttpResponse('message');
        }
    }
}

return new MyHandler();
