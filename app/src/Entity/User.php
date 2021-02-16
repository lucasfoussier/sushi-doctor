<?php
namespace App\Entity;

use Ramsey\Uuid\Uuid;
use JLucki\ODM\Spark\Attribute\AttributeName;
use JLucki\ODM\Spark\Attribute\AttributeType;
use JLucki\ODM\Spark\Attribute\GlobalSecondaryIndex;
use JLucki\ODM\Spark\Attribute\KeyType;
use JLucki\ODM\Spark\Attribute\ProjectionType;
use JLucki\ODM\Spark\Attribute\ReadCapacityUnits;
use JLucki\ODM\Spark\Attribute\TableName;
use JLucki\ODM\Spark\Attribute\WriteCapacityUnits;
use JLucki\ODM\Spark\Model\Base\Item;
use Symfony\Component\Security\Core\User\UserInterface;

#[
    TableName(DYNAMODB_TABLE_PREFIX.'user'),
    WriteCapacityUnits(1),
    ReadCapacityUnits(1)
]
class User extends Item implements UserInterface
{

    #[
        KeyType('HASH'),
        AttributeName('id'),
        AttributeType('S'),
    ]
    private string $id;

    #[
        KeyType('HASH'),
        AttributeName('email'),
        AttributeType('S'),
        GlobalSecondaryIndex,
        WriteCapacityUnits(1),
        ReadCapacityUnits(1),
        ProjectionType(ProjectionType::ALL),
    ]
    private string $email;

    #[
        AttributeName('password'),
        AttributeType('S'),
    ]
    private string $password;

    #[
        AttributeName('userRoles'),
        AttributeType('S'),
    ]
    private string $userRoles;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->setRoles(['ROLE_USER']);
        parent::__construct();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setRoles(array $roles): self
    {
        $roles = json_encode($roles);
        $this->setUserRoles($roles);

        return $this;
    }

    public function getRoles(): array
    {
        return json_decode($this->getUserRoles());
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->setPassword('');
    }

    /**
     * @return string
     */
    public function getUserRoles(): string
    {
        return $this->userRoles;
    }

    /**
     * @param string $userRoles
     */
    public function setUserRoles(string $userRoles): void
    {
        $this->userRoles = $userRoles;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }
}
