<?php
declare(strict_types=1);
namespace App\Entity;

use DateTime;
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
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface;
use Ramsey\Uuid\Uuid;

#[
    TableName(DYNAMODB_TABLE_PREFIX.'user.refresh.token'),
    WriteCapacityUnits(1),
    ReadCapacityUnits(1)
]
class UserRefreshToken extends Item implements RefreshTokenInterface
{

    #[
        KeyType('HASH'),
        AttributeName('refreshToken'),
        AttributeType('S'),
    ]
    private string $refreshToken;

    #[
        OpenAttribute('username'),
        AttributeType('S'),
    ]
    private string $username;

    #[
        OpenAttribute('valid'),
        AttributeType('N'),
    ]
    private DateTime $valid;


    public function getId()
    {
        return $this->refreshToken;
    }

    /**
     * Set refreshToken.
     *
     * @param string $refreshToken
     *
     * @return UserRefreshToken
     */
    public function setRefreshToken($refreshToken = null)
    {
        $this->refreshToken = null === $refreshToken
            ? bin2hex(openssl_random_pseudo_bytes(64))
            : $refreshToken
        ;

        return $this;
    }

    /**
     * Get refreshToken.
     *
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * Set valid.
     *
     * @param DateTime $valid
     *
     * @return UserRefreshToken
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * Get valid.
     *
     * @return DateTime
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return UserRefreshToken
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Check if is a valid refresh token.
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->valid >= new DateTime();
    }

    /**
     * @return string Refresh Token
     */
    public function __toString()
    {
        return $this->getRefreshToken();
    }
}
