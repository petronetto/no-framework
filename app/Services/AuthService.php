<?php

declare(strict_types=1);

namespace HelloFresh\Services;

use HelloFresh\Models\User;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;
use Petronetto\Config;
use Petronetto\Exceptions\UnauthorizedException;

class AuthService
{
    /** @var array */
    private $config = [];

    public function __construct()
    {
        $config                 = Config::getInstance();
        $this->config           = $config->get('jwt');
        $this->config['issuer'] = $config->get('app.url');
    }

    /**
     * @return array
     */
    public function getConfigs(): array
    {
        return $this->config;
    }

    /**
     * @param  string                $username
     * @param  string                $password
     * @return integer
     * @throws UnauthorizedException
     */
    public function authorize(string $username, string $password): int
    {
        if (!$user = User::where('username', $username)->first()) {
            throw new UnauthorizedException('Invalid credentials');
        }

        if (!password_verify($password, $user->password)) {
            throw new UnauthorizedException('Invalid credentials');
        }

        return $user->id;
    }

    /**
     * Create a JWT Token
     *
     * @param  integer $id
     * @return string
     */
    public function getToken(): string
    {
        $signer = new Sha256();

        return (string) (new Builder())->setIssuer($this->config['issuer'])
            ->setId($this->config['jti'], true)
            ->setIssuedAt(time())
            ->setExpiration(time() + $this->config['ttl'])
            ->sign($signer, $this->config['secret'])
            ->getToken();
    }

    /**
     * @param  string                $token
     * @return void
     * @throws UnauthorizedException
     */
    public function validateToken(Token $token): void
    {
        $signer = new Sha256();

        if (!$token->verify($signer, $this->config['secret'])) {
            throw new UnauthorizedException('Invalid token');
        }

        if ($token->isExpired()) {
            throw new UnauthorizedException('Token expired');
        }

        $validationData = new ValidationData();
        $validationData->setIssuer($this->config['issuer']);
        $validationData->setId($this->config['jti']);

        if (!$token->validate($validationData)) {
            throw new UnauthorizedException('Invalid token');
        }
    }
}
