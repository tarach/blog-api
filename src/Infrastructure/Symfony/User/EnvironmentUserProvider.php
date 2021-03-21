<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\User;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class EnvironmentUserProvider implements UserProviderInterface
{
    private string $username;
    private string $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function loadUserByUsername(string $username): EnvironmentUser
    {
        if ($this->username !== $username) {
            $message = sprintf('User identified by name [%s] does not exists.', $username);
            throw new UsernameNotFoundException($message);
        }

        return new EnvironmentUser(
            $this->username,
            $this->password
        );
    }

    public function refreshUser(UserInterface $user): EnvironmentUser
    {
        if ($user instanceof EnvironmentUser) {
            return $user;
        }

        throw new UnsupportedUserException(sprintf('User [%s] is not supported.', get_class($user)));
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass(string $class): bool
    {
        return EnvironmentUser::class === $class;
    }
}
