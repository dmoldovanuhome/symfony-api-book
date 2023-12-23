<?php

namespace App\Wrapper;

use Doctrine\Common\Collections\ArrayCollection;

class ApiPage
{
    private $content;
    private $total;
    private $pages;
    private $offset;
    private $limit;

    public function __construct()
    {
        $this->content = new ArrayCollection();
    }

    public static function of(ArrayCollection $content, int $totalElements, int $offset = 0, int $limit = 20): ApiPage
    {
        $page = new ApiPage();
        $pageCount = ceil($totalElements / $limit);
        $page->setContent($content)
            ->setTotal($totalElements)
            ->setPages($pageCount)
            ->setOffset($offset)
            ->setLimit($limit);

        return $page;
    }

    /**
     * @param ArrayCollection $content
     * @return ApiPage
     */
    public function setContent(ArrayCollection $content): ApiPage
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @param mixed $total
     * @return ApiPage
     */
    public function setTotal($total): ApiPage
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @param mixed $offset
     * @return ApiPage
     */
    public function setOffset($offset): ApiPage
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param mixed $limit
     * @return ApiPage
     */
    public function setLimit($limit): ApiPage
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getContent(): ArrayCollection
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return mixed
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $pages
     * @return ApiPage
     */
    public function setPages($pages): ApiPage
    {
        $this->pages = $pages;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPages()
    {
        return $this->pages;
    }
}