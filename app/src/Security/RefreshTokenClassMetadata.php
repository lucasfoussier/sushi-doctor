<?php
namespace App\Security;

use App\Entity\UserRefreshToken;

class RefreshTokenClassMetadata
{
    public function getName()
    {
        return UserRefreshToken::class;
    }
}
