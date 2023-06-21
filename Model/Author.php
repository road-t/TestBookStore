<?php

require_once ('Entity.php');
require_once "helpers.php";

class Author extends Entity
{
    static string $table = 'authors';

    protected string $name;

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
}