<?php

namespace App\Entity;

use App\Controller\OneToManyEntity as ControllerOneToManyEntity;
use App\Repository\CategoryRepository;
use App\Entity\OneToManyEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category implements \JsonSerializable, IOneToManyEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="category")
     */
    private $videos;

    public function __construct(string $title, string $color)
    {
        $this->title = $title;
        $this->color = $color;
        $this->videos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setCategory($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getCategory() === $this) {
                $video->setCategory(null);
            }
        }

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'color' => $this->getColor(),
            '_links' => [
                [
                    'rel' => 'self',
                    'path' => '/categories/' . $this->getId()
                ],
            ]
        ];
    }

    public function removeEntity($category)
    {
        $videos = $this->getVideos();
        
        foreach ($videos as $video) {
            $video->setCategory($category);
        }

        return $this;
    }
}
