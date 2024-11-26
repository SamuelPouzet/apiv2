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

    public function __construct(protected array $config)
    {
        $this->setTokenBuilder(new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $this->setAlgorithm(new Sha256());
        $this->setSigningKey(InMemory::base64Encoded($this->config['payload'] ?? 'mBC5v1sOKVvbdEitdSBenu59nfNfhwkedkJVNabosTw='));

        $this->tokenBuilder->identifiedBy( $config['tokenId'] ?? 'purple-auth')
            // Configures the issuer (iss claim)
            ->issuedBy($config['issuedBy'] ?? 'https://example.com')
            // Configures the audience (aud claim)
            ->permittedFor($config['permittedFor'] ?? 'http://example.org')
            // Configures the subject of the token (sub claim)
            ->relatedTo($config['relatedTo'] ?? 'component1')
            ->issuedAt(new \DateTimeImmutable());
    }

    public function getTokenBuilder(): BuilderInterface
    {
        return $this->tokenBuilder;
    }

    public function expiresAt(\DateTimeImmutable $date): static
    {
        $this->tokenBuilder->expiresAt($date);
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