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

    /**
     * @return string
     */
    public function getConnectionId(): string
    {
        return $this->connectionId;
    }

    /**
     * @param string $connectionId
     */
    public function setConnectionId(string $connectionId): void
    {
        $this->connectionId = $connectionId;
    }

    /**
     * @return string
     */
    public function getApiId(): string
    {
        return $this->apiId;
    }

    /**
     * @param string $apiId
     */
    public function setApiId(string $apiId): void
    {
        $this->apiId = $apiId;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @param string $region
     */
    public function setRegion(string $region): void
    {
        $this->region = $region;
    }

    /**
     * @return string
     */
    public function getStage(): string
    {
        return $this->stage;
    }

    /**
     * @param string $stage
     */
    public function setStage(string $stage): void
    {
        $this->stage = $stage;
    }


}
