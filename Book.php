<?php

require_once ('Entity.php');
require_once('Author.php');
require_once("utils.php");

class Book extends Entity
{
    static string $table = 'books';

    public function __construct(
        private ?int $id,
        private string $title,
        private float $price,
        private Author $author,
        private bool $isDirty = false) {}

    public function getId() { return $this->id; }

    public function getTitle() : string { return $this->title; }
    public function getPrice() : float { return $this->price; }
    public function getAuthor() : Author { return $this->author; }

    public function setTitle(string $title) : bool
    {
        $newTitle = homologateString($title);

        if ($newTitle != null)
        {
            $this->title = $newTitle;
            $this->isDirty = true;
        }

        return false;
    }

    static public function load(int $id) : ?Book
    {
        return null;
    }

    public function save() : bool
    {
        if (!$this->isDirty) {
            return false;
        }

        // save here

        return true;
    }

    public function delete() : bool
    {
        // delete or die
        return true;
    }
}