<?php

namespace App\Security;

use App\Repository\UserRefreshTokenRepository;
use Doctrine\Persistence\ObjectManager;
use JLucki\ODM\Spark\Interface\ItemInterface;
use JLucki\ODM\Spark\Spark;

class RefreshTokenObjectManager implements ObjectManager
{

    public function __construct(
        private UserRefreshTokenRepository $refreshTokenRepository,
        private RefreshTokenClassMetadata $refreshTokenClassMetadata,
        private Spark $spark
    ){}

    public function persist($object)
    {
        /* @var $object ItemInterface */
        $this->spark->putItem($object);
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
        // Unused in RefreshToken context
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
