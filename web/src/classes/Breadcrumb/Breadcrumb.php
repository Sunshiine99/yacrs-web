<?php

class Breadcrumb
{

    /** @var array */
    private $items;

    /**
     * Breadcrumb constructor.
     * @param array $items
     */
    public function __construct(array $items = []) {
        $this->items = $items;
    }

    /**
     * Adds new item to array of items
     * @param string $title
     * @param string $link
     */
    public function addItem($title = null, $link = null) {
        array_push($this->items, new BreadcrumbItem($title, $link));
    }

    /**
     * Counts the number of breadcrumb items
     * @return int
     */
    public function count() {
        return count($this->items);
    }

    /**
     * @return array
     */
    public function getItems() {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems($items) {
        $this->items = $items;
    }
}