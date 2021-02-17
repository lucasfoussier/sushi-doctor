<?php

namespace App\Repository;

use App\Entity\UserRefreshToken;
use DateTime;
use JLucki\ODM\Spark\Exception\QueryException;
use JLucki\ODM\Spark\Query\Expression;
use JLucki\ODM\Spark\Query\Query;
use JLucki\ODM\Spark\Spark;

class UserRefreshTokenRepository
{

    public function __construct(
        private Spark $spark,
    ){}

    /**
     * @return Query
     */
    private function getQueryBuilder(): Query
    {
        return $this->spark->query(UserRefreshToken::class);
    }


    /**
     * @param array $params
     * @return UserRefreshToken|null
     * @throws QueryException
     */
    public function findOneBy(array $params): ?UserRefreshToken
    {
        $userRefreshToken = $this->getQueryBuilder();
        foreach ($params as $key => $value){
            $userRefreshToken->findBy((new Expression())->attribute($key)->value($value));
        }
        /* @var $result UserRefreshToken */
        $result = $userRefreshToken->getFirst();
        return $result;
    }

    /**
     * @param DateTime|null $datetime
     * @return UserRefreshToken[]
     */
    public function findInvalid(DateTime $datetime = null)
    {
        $allItems = $this->spark->scan(UserRefreshToken::class);
        $datetime = (null === $datetime) ? new DateTime() : $datetime;
        foreach ($allItems as $key => $value){
            if ($value->getValid() >= $datetime){
                unset($allItems[$key]);
            }
        }
        $allItems = array_values($allItems);
        /* @var $allItems UserRefreshToken[] */
        return $allItems;
    }


}
