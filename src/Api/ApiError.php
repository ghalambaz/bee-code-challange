<?php


namespace App\Api;


class ApiError
{
    const TYPE_VALIDATION = 'validation_error';
    const TYPE_PARSE_REQUEST_CONTENT = 'invalid_body_content_error';
    const TYPE_INTERNAL = 'internal_error';
    const TYPE_UNKNOWN = 'unknown_error';
    const TYPE_ITEM_NOT_FOUND = 'item_notfound_error';
    const TYPE_BLANK = 'about:blank';
    const TYPE_BAD_CREDENTIAL = 'bad_credential_error';

    private $defaultTitles = [
        self::TYPE_VALIDATION => 'validation error',
        self::TYPE_PARSE_REQUEST_CONTENT => 'we have some issue in parsing your request content',
        self::TYPE_INTERNAL => 'internal server error',
        self::TYPE_UNKNOWN => 'unknown error',
        self::TYPE_ITEM_NOT_FOUND => 'item not found!',
        self::TYPE_BLANK => 'Not Found',
        self::TYPE_BAD_CREDENTIAL => 'user or password is wrong'
    ];
    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $title;

    /**
     * @var array
     */
    private $content = [];

    /**
     * ApiError constructor.
     * @param int $statusCode
     * @param string $type
     * @param string|null $title
     * @param array $content
     */
    public function __construct(
        int $statusCode,
        string $type = self::TYPE_BLANK,
        array $content = [],
        string $title = null
    ) {
        if (is_null($title)) {
            if (!empty($this->defaultTitles[$type])) {
                $title = $this->defaultTitles[$type];
            }
        }
        $this->statusCode = $statusCode;
        $this->type = $type;
        $this->title = $title;
        $this->content = $content;
    }


    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
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
     * @return ApiError
     */
    public function setTitle(string $title): ApiError
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return array
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * @param string $key
     * @param $content
     * @return ApiError
     */
    public function setContent(string $key, $content): ApiError
    {
        $this->content[$key] = $content;
        return $this;
    }

    public function getArray()
    {
        return array_merge(
            $this->content,
            [
                'title' => $this->title,
                'type' => $this->type,
                'status' => $this->statusCode
            ]
        );
    }

    /**
     * @param string $type
     * @return ApiError
     */
    public function setType(string $type, bool $autoTitle = true): ApiError
    {
        $this->type = $type;
        if (isset($this->defaultTitles[$type])) {
            $this->title = $this->defaultTitles[$type];
        }
        return $this;
    }

}
