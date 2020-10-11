<?php


namespace App\Form\Model;


use App\Entity\Artist;

class RecordModel
{
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $description;
    /**
     * @var int
     */
    private $price;
    /**
     * @var Artist
     */
    private $artist;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return RecordModel
     */
    public function setTitle(string $title): RecordModel
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
     * @return RecordModel
     */
    public function setDescription(string $description): RecordModel
    {
        $this->description = $description;
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
     * @return RecordModel
     */
    public function setPrice(int $price): RecordModel
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return Artist
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * @param Artist $artist
     * @return RecordModel
     */
    public function setArtist(Artist $artist): RecordModel
    {
        $this->artist = $artist;
        return $this;
    }


}
