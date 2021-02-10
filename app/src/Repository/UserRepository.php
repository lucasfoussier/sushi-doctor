<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\NonUniqueResultException;
use JLucki\ODM\Spark\Exception\ItemActionFailedException;
use JLucki\ODM\Spark\Exception\QueryException;
use JLucki\ODM\Spark\Query\Expression;
use JLucki\ODM\Spark\Query\Query;
use JLucki\ODM\Spark\Spark;

class UserRepository
{

    public function __construct(
        private Spark $spark,
    ){}

    /**
     * @return Query
     */
    private function getQueryBuilder(): Query
    {
        return $this->spark->query(User::class);
    }

    /**
     * @param User $user
     * @return bool
     * @throws ItemActionFailedException
     * @throws NonUniqueResultException
     * @throws QueryException
     */
    public function insertNewUser(User $user): bool
    {
        if (null !== $this->findOneByEmail($user->getEmail())) {
            return false;
        }
        $this->spark->putItem($user);
        return true;
    }

    /**
     * @param string $email
     * @return User|null
     * @throws NonUniqueResultException
     * @throws QueryException
     */
    public function findOneByEmail(string $email): ?User
    {
        $users = $this->getQueryBuilder()
            ->indexName('email')
            ->findBy((new Expression())->attribute('email')->value($email))
            ->getItems()
        ;
        if(count($users) > 1){
            throw new NonUniqueResultException('More than one row found for this user');
        } else if (count($users) === 0){
            return null;
        }
        /* @var $users User */
        return $users[0];
    }
}
