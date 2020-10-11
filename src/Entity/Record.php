<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RecordsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="record", indexes={@ORM\Index(name="idx_artist", columns={"artist_id"})})
 * @ORM\Entity(repositoryClass=RecordsRepository::class)
 */
class Record
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"unsigned"=true})
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @var Artist
     *
     * @ORM\ManyToOne(targetEntity="Artist")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="artist_id", referencedColumnName="id")
     * })
     */
    private $artist;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer", nullable=false)
     */
    private $price;

    /**
     * Record constructor.
     * @param string $title
     * @param string $description
     * @param Artist $artist
     * @param int $price
     */
    public function __construct(string $title, string $description, Artist $artist, int $price)
    {
        $this->title = $title;
        $this->description = $description;
        $this->artist = $artist;
        $this->price = $price;
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Artist
     */
    public function getArtist(): Artist
    {
        return $this->artist;
    }

    /**
     * @param Artist $artist
     * @return $this
     */
    public function setArtist(Artist $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     * @return $this
     */
    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }
}
