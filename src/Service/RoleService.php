<?php

namespace SamuelPouzet\Api\Service;

use Doctrine\ORM\EntityManager;
use Laminas\Cache\Storage\StorageInterface;
use SamuelPouzet\Api\Entity\Role;
use SamuelPouzet\Api\Entity\User;

class RoleService
{
    protected array $roles;

    public function __construct(
        protected EntityManager $entityManager,
        protected StorageInterface $cache
    )
    {
    }

    public function isRoleAllowedForUser(User $user, array $roles): bool
    {
        if (! isset($this->roles)) {
            $this->generateRoles();
        }

        foreach ($user->getRoles() as $userRole) {
            if (
                isset($this->roles[$userRole->getCode()])
                && 0 !== count(array_intersect($this->roles[$userRole->getCode()], $roles))
            ) {
                return true;
            }
        }

        return false;
    }

    public function generateRoles(): array
    {
        if (isset($this->roles)) {
            return $this->roles;
        }

        $this->roles = $this->cache->getItem('rbac_container', $result) ?? [];
        if (! $result) {
            $entities = $this->entityManager->getRepository(Role::class)->findAll();
            foreach ($entities as $entity) {
                $this->roles[$entity->getCode()] = $this->parseChildren($entity);
            }

            $this->cache->addItem('rbac_container', $this->roles);
        }
        return $this->roles;
    }

    private function parseChildren(Role $role): array
    {
        $arr = [$role->getCode()];
        foreach ($role->getChildRoles() as $child) {
            $arr = array_merge($arr, $this->parseChildren($child));
        }
        return $arr;
    }
}
