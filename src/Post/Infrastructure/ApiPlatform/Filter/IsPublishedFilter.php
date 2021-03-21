<?php

declare(strict_types=1);

namespace App\Post\Infrastructure\ApiPlatform\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class IsPublishedFilter extends AbstractContextAwareFilter
{
    protected function filterProperty(
        string $property,
        $value,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ) {
        if ('isPublished' !== $property) {
            return;
        }

        $value = $this->sanitizeValue($value);
        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder
            ->andWhere($rootAlias . '.isPublished = :isPublished')
            ->setParameter('isPublished', $value)
        ;
    }

    private function sanitizeValue($value): bool
    {
        if ('true' === $value || 1 === $value) {
            return true;
        }

        if ('false' === $value || 0 === $value) {
            return false;
        }

        throw new BadRequestHttpException('Invalid param isPublished value.');
    }

    public function getDescription(string $resourceClass): array
    {
        return [
            'isPublished' => [
                'property' => 'isPublished',
                'type' => 'bool',
                'required' => false,
                'swagger' => [
                    'description' => 'Filter posts that are published or not.',
                    'name' => 'Is published',
                    'type' => '',
                ],
            ],
        ];
    }
}
