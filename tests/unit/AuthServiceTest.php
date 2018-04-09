<?php

declare(strict_types=1);

use HelloFresh\Services\AuthService;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;

class AuthServiceTest extends BaseTest
{
    /**
     * @covers \HelloFresh\Services\AuthService::getToken
     * @uses   \HelloFresh\Services\AuthService
     */
    public function test_get_token()
    {
        $service = new AuthService();

        $token = $service->getToken();

        $this->assertEquals(true, is_string($token));
    }

    /**
     * @covers \HelloFresh\Services\AuthService::validateToken
     * @uses   \HelloFresh\Services\AuthService
     * @expectedException \Petronetto\Exceptions\UnauthorizedException
     */
    public function test_invalid_token()
    {
        $service = new AuthService();

        $anotherToken = (new Builder())->setIssuer('http://something.com')
            ->setId('another_jti', true)
            ->setIssuedAt(time())
            ->setExpiration(time() + 60)
            ->sign((new Sha256()), 'another_secret')
            ->getToken();

        $service->validateToken($anotherToken);
    }

    /**
     * @covers \HelloFresh\Services\AuthService::validateToken
     * @uses   \HelloFresh\Services\AuthService
     * @expectedException \Petronetto\Exceptions\UnauthorizedException
     */
    public function test_expeired_token()
    {
        $service = new AuthService();

        $config = $service->getConfigs();

        $token = (string) (new Builder())->setIssuer($config['issuer'])
            ->setId($config['jti'], true)
            ->setIssuedAt(time() - 120)
            ->setExpiration(time() - 60)
            ->sign((new Sha256()), $config['secret'])
            ->getToken();

        $token = (new Parser())->parse($token);

        $service->validateToken($token);
    }
}
