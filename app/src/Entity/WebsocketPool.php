<?php

declare(strict_types=1);

namespace App\Entity;

use JLucki\ODM\Spark\Attribute\AttributeName;
use JLucki\ODM\Spark\Attribute\AttributeType;
use JLucki\ODM\Spark\Attribute\KeyType;
use JLucki\ODM\Spark\Attribute\TableName;
use JLucki\ODM\Spark\Attribute\WriteCapacityUnits;
use JLucki\ODM\Spark\Attribute\ReadCapacityUnits;
use JLucki\ODM\Spark\Model\Base\Item;

#[
//    TableName(DYNAMODB_TABLE_PREFIX.'websocket.pool'),
    TableName('websocket.pool'), // TODO: add prefix
    WriteCapacityUnits(1),
    ReadCapacityUnits(1)
]
class WebsocketPool extends Item
{

    #[
        KeyType('HASH'),
        AttributeName('connectionId'),
        AttributeType('S'),
    ]
    private string $connectionId;

    #[
        AttributeName('apiId'),
        AttributeType('S'),
    ]
    private string $apiId;

    #[
        AttributeName('region'),
        AttributeType('S'),
    ]
    private string $region;

    #[
        AttributeName('stage'),
        AttributeType('S'),
    ]
    private string $stage;



}
