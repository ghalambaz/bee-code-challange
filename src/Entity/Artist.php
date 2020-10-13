<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ArtistsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="artist")
 * @ORM\Entity(repositoryClass=ArtistsRepository::class)
 */
class Artist
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     *
     * @Assert\Type("string")
     * @Assert\Length(min="1", max="100")
     * @Assert\Regex("/^[^<>]+$/i")
     */
    private $name;

    /**
     * @var Collection|Record[]
     *
     * @ORM\OneToMany(targetEntity="Record", mappedBy="artist")
     */
    private $records;

    public function __construct()
    {
        $this->records = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
