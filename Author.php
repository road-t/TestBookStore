<?php

require_once ('Entity.php');
require_once "helpers.php";

class Author extends Entity
{
    static string $table = 'author';

    public function __construct(
        private ?int $id,
        private string $name,
        private bool $isDirty = false) {}

    public function getId() : int { return $this->id; }
    public function getName() : string { return $this->name; }

    public function setName(string $name) : bool
    {
        $newName = homologateString($name);

        if ($newName != null)
        {
            $this->name = $newName;
            $this->isDirty = true;
        }

        return false;
    }

    static public function load(int $id) : ?Author
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