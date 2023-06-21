<?php

require_once("helpers.php");
require_once("DB.php");

abstract class Entity
{
    public static string $table = '';

    protected ?int $id = null;
    protected bool $isDirty = false;

    public function __construct() {}

    public function getId() { return $this->id; }

    public function serialize()
    {
        $vars = get_object_vars($this);

        unset($vars['isDirty']);
        unset($vars['deleted']);

        return $vars;
    }

    public static function createFromArray(array $array) : Entity
    {
        $entity = new static();

        foreach ($array as $key => $value) {
            if ($key == 'id' || $key == 'deleted') {
                continue;
            }

            if (property_exists($entity, $key)) {
                $entity->{$key} = $value;
            }
        }

        return $entity;
    }

    public function updateFromArray(array $array)
    {
        foreach ($array as $key => $value) {
            if ($key == 'id' || $key == 'deleted') {
                continue;
            }

            if (property_exists($this, $key) &&
                    (
                        (isset($this->{$key}) && $this->{$key} != $value) ||
                        !isset($this->{$key})
                    )
            ) {
                $this->{$key} = $value;
                $this->isDirty = true;
            }
        }
    }

    static public function show(int $id) : ?Entity
    {
        $entity = new static();
        return DB::selectOne($entity, $id);
    }

    static public function list(int $limit = null) : array
    {
        $entity = new static();
        return DB::selectAll($entity, $limit);
    }

    public function save() : bool
    {
        if (!$this->isDirty) {
            return false;
        }

        if ($this->id) {
            DB::update($this);
        } else {
            DB::insert($this);
        }

        return true;
    }

    public function delete() : bool
    {
        return DB::delete($this);
    }
}