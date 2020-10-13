<?php


namespace App\Form\Model;


use App\Entity\Artist;

class RecordModel
{
    /**
     * @var ?string
     */
    private $title;
    /**
     * @var ?string
     */
    private $description;
    /**
     * @var ?int
     */
    private $price;
    /**
     * @var ?Artist
     */
    private $artist;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return RecordModel
     */
    public function setTitle(?string $title): RecordModel
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return RecordModel
     */
    public function setDescription(?string $description): RecordModel
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @param int|null $price
     * @return RecordModel
     */
    public function setPrice(?int $price): RecordModel
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return Artist|null
     */
    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    /**
     * @param Artist|null $artist
     * @return RecordModel
     */
    public function setArtist(?Artist $artist): RecordModel
    {
        $this->artist = $artist;
        return $this;
    }


}
