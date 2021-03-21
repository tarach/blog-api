<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use App\Post\Infrastructure\ApiPlatform\Filter\IsPublishedFilter;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "post",
 *          "get"={
 *              "method"="GET",
 *              "filters"={
 *                  IsPublishedFilter::class
 *              },
 *          },
 *     },
 *     itemOperations={
 *          "get",
 *          "patch",
 *          "delete"
 *     }
 * )
 * @ORM\Entity()
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private Uuid $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private ?string $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $body;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isPublished;

    public function __construct(?string $title, ?string $body, bool $isPublished = false)
    {
        $this->id = Uuid::v4();
        $this->title = $title;
        $this->body = $body;
        $this->isPublished = $isPublished;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function isPublished(): ?bool
    {
        return $this->isPublished;
    }
}
