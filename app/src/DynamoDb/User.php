<?php
namespace App\DynamoDb;

/**
 * @property string id
 * @property string|null lastName
 * @property string|null firstName
 */
class User extends Model
{
    public function __construct()
    {
        $this->setId(self::getUuid());
    }
    public static $tableName = 'user';
    public static function schema() :array
    {
        return [
            "TableName" => self::convertTableName(self::$tableName),
            "AttributeDefinitions" => [
                [
                    'AttributeName' => 'id',
                    'AttributeType' => 'S',
                ]
            ],
            'KeySchema' => [
                [
                    'AttributeName' => 'id',
                    'KeyType' => 'HASH',
                ]
            ],
            'ProvisionedThroughput' => [
                'ReadCapacityUnits' => 5,
                'WriteCapacityUnits' => 5,
            ],
        ];
    }

    public function getId():string{
        return $this->id;
    }

    public function setId(string $id):self{
        $this->id = $id;
        return $this;
    }

    public function getFirstName():string{
        return $this->firstName;
    }

    public function setFirstName(string $firstName):self{
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName():string{
        return $this->lastName;
    }

    public function setLastName(string $lastName):self{
        $this->lastName = $lastName;
        return $this;
    }

}
