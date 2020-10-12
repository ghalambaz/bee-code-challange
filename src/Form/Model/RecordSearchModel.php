<?php


namespace App\Form\Model;


class RecordSearchModel
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
     * @var ?string
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
     * @return RecordSearchModel
     */
    public function setTitle(?string $title): RecordSearchModel
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
     * @return RecordSearchModel
     */
    public function setDescription(?string $description): RecordSearchModel
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getArtist(): ?string
    {
        return $this->artist;
    }

    /**
     * @param string|null $artist
     * @return RecordSearchModel
     */
    public function setArtist(?string $artist): RecordSearchModel
    {
        $this->artist = $artist;
        return $this;
    }

}
