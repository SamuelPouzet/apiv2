<?php

namespace SamuelPouzet\Api\Service;

use Laminas\Http\Cookies;
use Laminas\Http\Header\SetCookie;
use Laminas\Http\Request;

class CookieService
{

    protected string $name;
    protected string $value;
    protected ?string $domain = null;
    protected \DateTimeImmutable $expirationDate;
    protected bool $httpOnly = true;
    protected bool $secure = false;
    protected ?int $maxAge = null;
    protected ?int $version = null;
    protected ?string $path = null;
    protected string $sameSite = 'Lax';

    public function getCookieContent(Request $request, string $cookieName): ?string
    {
        $cookie = $request->getCookie();
        return $cookie && $cookie->offsetExists($cookieName) ? $cookie->offsetGet($cookieName) : null;
    }

    public function addCookie(): SetCookie
    {
        return new SetCookie(
            $this->name,
            $this->value,
            $this->expirationDate->format('Y-m-d H:i:s'),
            $this->path,
            $this->domain,
            $this->secure,
            $this->httpOnly,
            $this->maxAge,
            $this->version,
            $this->sameSite
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): CookieService
    {
        $this->name = $name;
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): CookieService
    {
        $this->value = $value;
        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(?string $domain): CookieService
    {
        $this->domain = $domain;
        return $this;
    }

    public function getExpirationDate(): \DateTimeImmutable
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(\DateTimeImmutable $expirationDate): CookieService
    {
        $this->expirationDate = $expirationDate;
        return $this;
    }

    public function isHttpOnly(): bool
    {
        return $this->httpOnly;
    }

    public function setHttpOnly(bool $httpOnly): CookieService
    {
        $this->httpOnly = $httpOnly;
        return $this;
    }

    public function isSecure(): bool
    {
        return $this->secure;
    }

    public function setSecure(bool $secure): CookieService
    {
        $this->secure = $secure;
        return $this;
    }

    public function getMaxAge(): ?int
    {
        return $this->maxAge;
    }

    public function setMaxAge(?int $maxAge): CookieService
    {
        $this->maxAge = $maxAge;
        return $this;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(?int $version): CookieService
    {
        $this->version = $version;
        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): CookieService
    {
        $this->path = $path;
        return $this;
    }

    public function getSameSite(): string
    {
        return $this->sameSite;
    }

    public function setSameSite(string $sameSite): CookieService
    {
        $this->sameSite = $sameSite;
        return $this;
    }

}