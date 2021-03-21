<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\User;

use Symfony\Component\Security\Core\User\UserInterface;

final class EnvironmentUser implements UserInterface
{
    private string $username;
    private ?string $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function getRoles(): array
    {
        return ['USER_ROLE'];
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function eraseCredentials(): void
    {
        $this->password = null;
    }
}
