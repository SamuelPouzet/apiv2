<?php

namespace SamuelPouzet\Api\Service;

use Lcobucci\JWT\Builder as BuilderInterface;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Signer\Hmac;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Validator;

class JWTService
{
    /**
     * @var Hmac
     */
    protected Hmac $algorithm;
    protected Key $signingKey;

    public function __construct(protected array $config)
    {
        $this->signingKey =
            InMemory::base64Encoded($this->config['payload'] ?? 'mBC5v1sOKVvbdEitdSBenu59nfNfhwkedkJVNabosTw=')
        ;

        $this->algorithm = new Sha256();
    }

    public function build(\DateTimeImmutable $expirationDate, array $claims = [], array $headers = []): Plain
    {
        /**
         * @var BuilderInterface
         */
         $tokenBuilder = new Builder(new JoseEncoder(), ChainedFormatter::default());


        foreach ($claims as $key => $value) {
            $tokenBuilder->withClaim($key, $value);
        }

        foreach ($headers as $key => $value) {
            $tokenBuilder->withHeader($key, $value);
        }

        $tokenBuilder
            ->expiresAt($expirationDate)
            ->identifiedBy($config['tokenId'] ?? 'purple-auth')
            ->issuedBy($config['issuedBy'] ?? 'https://example.com')
            ->permittedFor($config['permittedFor'] ?? 'http://example.org')
            ->relatedTo($config['relatedTo'] ?? 'component1')
            ->issuedAt(new \DateTimeImmutable());

        return $tokenBuilder->getToken($this->algorithm, $this->signingKey);
    }

    public function readJwt(string $tokenContent): ?Token
    {
        try {
            $parser = new Parser(new JoseEncoder());
            $token = $parser->parse($tokenContent);

            $validator = new Validator();
            if ($validator->validate($token, new SignedWith($this->algorithm, $this->signingKey))) {
                return $token;
            }
            return null;
        } catch(\Exception $e) {
            return null;
        }
    }

}
