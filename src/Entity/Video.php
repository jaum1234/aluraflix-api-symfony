<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\VideoRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=VideoRepository::class)
 * 
 */
class Video implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Title field can't be empty.")
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank(message="Description field can't be empty.")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Url field can't be empty.")
     * @Assert\Url(message="This is not a valid url. It should be something like: http://exemple.com")
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="videos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    

    public function __construct(string $title, string $description, string $url, Category $category)
    {
        $this->title = $title;
        $this->description = $description;
        $this->url = $url;
        $this->category = $category;
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function updatePropertiesValues(array $values, Category $category): self
    {
        $this->title = $values['title'];
        $this->description = $values['description'];
        $this->url = $values['url'];
        $this->category = $category; 

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'url' => $this->getUrl(),
            'categoryId' => $this->getCategory()->getId(),
            '_links' => [
                [
                    'rel' => 'self',
                    'path' => '/videos/' . $this->getId()
                ],
                [
                    'rel' => 'especialidades',
                    'path' => '/categories/' . $this->getCategory()->getId()
                ]
            ]
        ];
    }
}
