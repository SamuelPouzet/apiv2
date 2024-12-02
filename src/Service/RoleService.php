<?php

namespace SamuelPouzet\Api\Service;

use Doctrine\ORM\EntityManager;
use SamuelPouzet\Api\Entity\Role;
use SamuelPouzet\Api\Entity\User;

class RoleService
{
    protected array $roles;

    public function __construct(protected EntityManager $entityManager)
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
        // todo mettre les roles en cache pour gagner lourdement en perfs
        if (isset($this->roles)) {
            return $this->roles;
        }

        $this->roles = [];
        $entities = $this->entityManager->getRepository(Role::class)->findAll();
        foreach ($entities as $entity) {
            $this->roles[$entity->getCode()] = $this->parseChildren($entity);
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
