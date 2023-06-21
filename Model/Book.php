<?php

require_once ('Entity.php');
require_once('Author.php');

class Book extends Entity
{
    static string $table = 'books';

    protected string $title;
    protected float $price;
    protected int $author_id;

    public function getTitle() : string { return $this->title; }
    public function getPrice() : float { return $this->price; }
    public function getAuthorId() : int { return $this->author_id; }
    public function getAuthor() : Author { return Author::show($this->author_id); }

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

    static public function list(int $limit = null) : array
    {
        $query = 'SELECT b.id, title, price, name author FROM '. Book::$table . ' b LEFT JOIN '. Author::$table .' a on a.id = author_id WHERE a.`deleted` IS NULL AND b.`deleted` IS NULL;';

        if ($limit !== null) {
            $query .= ' LIMIT ' . $limit;
        }

        return DB::selectCustom($query);
    }
}