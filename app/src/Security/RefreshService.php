<?php
namespace App\Security;

use App\Service\ResponseService;
use Symfony\Component\HttpFoundation\Request;
use Gesdinet\JWTRefreshTokenBundle\Service\RefreshToken;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RefreshService
{
    public function __construct(
        private RefreshToken $refreshToken,
        private ResponseService $responseService
    ) {}

    /**
     * @param Request $request
     * @return mixed
     */
    public function refresh(Request $request): mixed
    {
        $refreshToken = $request->cookies->get('REFRESH_TOKEN');
        if(is_null($refreshToken)){
            throw new AccessDeniedHttpException('Refresh Token not found');
        }
        $request->request->set('refresh_token', $refreshToken);
        return $this->refreshToken->refresh($request);
    }
}
