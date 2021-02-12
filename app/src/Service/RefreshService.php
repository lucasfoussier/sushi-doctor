<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Gesdinet\JWTRefreshTokenBundle\Service\RefreshToken;

class RefreshService
{
    public function __construct(
        private RefreshToken $refreshToken
    ) {}

    public function refresh(Request $request)
    {
        $refreshToken = $request->cookies->get('REFRESH_TOKEN');
        //TODO : return 401 if $refreshToken is null
        $request->request->set('refresh_token', $refreshToken);
        return $this->refreshToken->refresh($request);
    }
}
