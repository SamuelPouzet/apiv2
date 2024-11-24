<?php

namespace SamuelPouzet\Api\Service;

use Lcobucci\JWT\Builder as BuilderInterface;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Signer\Hmac;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token\Plain;

class JWTService
{

    protected BuilderInterface $tokenBuilder;
    protected Hmac $algorithm;
    protected Key $signingKey;

    public function __construct()
    {
        $this->setTokenBuilder(new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $this->setAlgorithm(new Sha256());
        $this->setSigningKey(InMemory::base64Encoded('mBC5v1sOKVvbdEitdSBenu59nfNfhwkedkJVNabosTw='));

        $this->tokenBuilder->identifiedBy('purple-auth')
            // Configures the issuer (iss claim)
            ->issuedBy('http://example.com')
            // Configures the audience (aud claim)
            ->permittedFor('http://example.org')
            // Configures the subject of the token (sub claim)
            ->relatedTo('component1')
            ->issuedAt(new \DateTimeImmutable());
    }

    public function getTokenBuilder(): BuilderInterface
    {
        return $this->tokenBuilder;
    }

    public function expiresAt(\DateInterval $interval): static
    {
        $this->tokenBuilder->expiresAt((new \DateTimeImmutable())->add($interval));
        return $this;
    }

    public function addClaim(string $name, string $value): static
    {
        $this->tokenBuilder->withClaim($name, $value);
        return $this;
    }

    public function generate(): Plain
    {
        return $this->tokenBuilder->getToken($this->algorithm, $this->signingKey);
    }

    public function setTokenBuilder(BuilderInterface $tokenBuilder): static
    {
        $this->tokenBuilder = $tokenBuilder;
        return $this;
    }

    public function setAlgorithm(Hmac $algorithm): static
    {
        $this->algorithm = $algorithm;
        return $this;
    }

    public function setSigningKey(Key $signingKey): static
    {
        $this->signingKey = $signingKey;
        return $this;
    }


}