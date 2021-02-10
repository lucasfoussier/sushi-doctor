<?php

namespace App\Security;

use App\Repository\UserRefreshTokenRepository;
use Doctrine\Persistence\ObjectManager;
use JLucki\ODM\Spark\Spark;

class RefreshTokenObjectManager implements ObjectManager
{

    public function __construct(
        private UserRefreshTokenRepository $refreshTokenRepository,
        private RefreshTokenClassMetadata $refreshTokenClassMetadata,
        private Spark $spark
    ){}

    public function find($className, $id)
    {
        dd('find');
        // TODO: Implement find() method.
    }

    public function persist($object)
    {
        $this->spark->putItem($object);
//        dd('persist');
        // TODO: Implement persist() method.
    }

    public function remove($object)
    {
        dd('remove');
        // TODO: Implement remove() method.
    }

    public function merge($object)
    {
        dd('merge');

        // TODO: Implement merge() method.
    }

    public function clear($objectName = null)
    {
        dd('clear');
        // TODO: Implement clear() method.
    }

    public function detach($object)
    {
        dd('detach');
        // TODO: Implement detach() method.
    }

    public function refresh($object)
    {
        dd('refresh');
        // TODO: Implement refresh() method.
    }

    public function flush()
    {
//        dd('flush');
        return;
        // TODO: Implement flush() method.
    }

    public function getRepository($className)
    {
        return $this->refreshTokenRepository;
        // TODO: Implement getRepository() method.
    }

    public function getClassMetadata($className)
    {
        return $this->refreshTokenClassMetadata;
        // TODO: Implement getClassMetadata() method.
    }

    public function getMetadataFactory()
    {
        dd('getMetadataFactory');
        // TODO: Implement getMetadataFactory() method.
    }

    public function initializeObject($obj)
    {
        dd('initializeObject');
        // TODO: Implement initializeObject() method.
    }

    public function contains($object)
    {
        dd('contains');
        // TODO: Implement contains() method.
    }
}
