<?php
namespace App\Security;

use App\Entity\UserRefreshToken;
use App\Repository\UserRefreshTokenRepository;
use Doctrine\Persistence\ObjectManager;
use JLucki\ODM\Spark\Exception\ItemActionFailedException;
use JLucki\ODM\Spark\Spark;

class RefreshTokenObjectManager implements ObjectManager
{

    public function __construct(
        private UserRefreshTokenRepository $refreshTokenRepository,
        private RefreshTokenClassMetadata $refreshTokenClassMetadata,
        private Spark $spark
    ){}

    /**
     * @param object $object
     * @throws ItemActionFailedException
     */
    public function persist($object)
    {
        /* @var $object UserRefreshToken */
        $itemByKey = $this->spark->getItem(UserRefreshToken::class, [
            'refreshToken' => $object->getRefreshToken(),
        ]);
        if(is_null($itemByKey)){
            $this->spark->putItem($object);
        } else {
            $this->spark->deleteItem($itemByKey);
            $this->spark->putItem($object);
        }
    }

    public function getRepository($className)
    {
        return $this->refreshTokenRepository;
    }

    public function getClassMetadata($className)
    {
        return $this->refreshTokenClassMetadata;
    }

    public function find($className, $id)
    {
        // Unused in RefreshToken context
    }

    public function remove($object)
    {
        $this->spark->deleteItem($object);
    }

    public function merge($object)
    {
        // Unused in RefreshToken context
    }

    public function clear($objectName = null)
    {
        // Unused in RefreshToken context
    }

    public function detach($object)
    {
        // Unused in RefreshToken context
    }

    public function refresh($object)
    {
        // Unused in RefreshToken context
    }

    public function flush()
    {
        return; // Unused in RefreshToken context
    }

    public function getMetadataFactory()
    {
        // Unused in RefreshToken context
    }

    public function initializeObject($obj)
    {
        // Unused in RefreshToken context
    }

    public function contains($object)
    {
        // Unused in RefreshToken context
    }
}
