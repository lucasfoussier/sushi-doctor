<?php

declare(strict_types=1);

namespace App\Entity;

use JLucki\ODM\Spark\Attribute\AttributeName;
use JLucki\ODM\Spark\Attribute\AttributeType;
use JLucki\ODM\Spark\Attribute\GlobalSecondaryIndex;
use JLucki\ODM\Spark\Attribute\KeyType;
use JLucki\ODM\Spark\Attribute\OpenAttribute;
use JLucki\ODM\Spark\Attribute\ProjectionType;
use JLucki\ODM\Spark\Attribute\TableName;
use JLucki\ODM\Spark\Attribute\WriteCapacityUnits;
use JLucki\ODM\Spark\Attribute\ReadCapacityUnits;
use JLucki\ODM\Spark\Model\Base\Item;
use DateTime;

#[
    TableName(DYNAMODB_TABLE_PREFIX.'service.rikudou'),
    WriteCapacityUnits(1),
    ReadCapacityUnits(1)
]
class Rikudou extends Item
{

    #[
        KeyType('HASH'),
        AttributeName('id'),
        AttributeType('S'),
    ]
    private string $id;


}
