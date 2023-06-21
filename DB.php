<?php

require_once 'config.php';
require_once 'Model/Entity.php';

class DB
{
    static private mysqli $db;

    static string $host = DB_HOST;
    static string $port = DB_PORT;
    static string $dbName = DB_NAME;
    static string $user = DB_USER;
    static string $password = DB_PASS;

    public static function connect()
    {
        self::$db = mysqli_connect(self::$host, self::$user, self::$password, self::$dbName, self::$port);
    }

    public static function selectOne(Entity $entity, $id) : ?Entity
    {
        $query = "SELECT * FROM {$entity::$table} WHERE `id` = '$id' AND `deleted` IS NULL LIMIT 1;";

        $result = self::query($query);

        return $result->fetch_object($entity::class);
    }

    public static function selectAll(Entity $entity, $limit = null) : array
    {
        $query = "SELECT * FROM {$entity::$table} WHERE `deleted` IS NULL ORDER BY `id` DESC";

        if ($limit !== null) {
            $query .= ' LIMIT ' . $limit;
        }

        $result = self::query($query);

        $list = [];

        while ($row = $result->fetch_object($entity::class)) {
            $list[] = $row;
        }

        return $list;
    }

    public static function selectCustom($query) : array
    {
        $result = self::query($query);

        $list = [];

        while ($row = $result->fetch_assoc()) {
            $list[] = $row;
        }

        return $list;
    }

    public static function insert(Entity $object)
    {
        $fields = $object->serialize();

        $columns = '';
        $values = '';

        foreach ($fields as $field => $value) {
            if ($field == 'id' || $field == 'deleted' || $field == 'isDirty') {
                continue;
            }

            $columns .= $field .', ';
            $values .= "'$value', ";
        }

        $columns = rtrim($columns, ', ');
        $values = rtrim($values, ', ');

        $query = "INSERT INTO {$object::$table} ($columns) VALUES ($values)";

        self::query($query);
    }

    public static function update(Entity $object)
    {
        $fields = $object->serialize();

        $values = '';

        foreach ($fields as $field => $value) {
            if ($field == 'id' || $field == 'isDirty' || $field == 'deleted') {
                continue;
            }

            $values .= "`$field` = '$value', ";
        }

        $values = rtrim($values, ', ');

        $query = "UPDATE {$object::$table} SET $values WHERE id = {$object->getId()} LIMIT 1";

        self::query($query);
    }

    public static function delete(Entity $object) : bool
    {
        $query = "UPDATE {$object::$table} SET `deleted` = NOW() WHERE `id` = '{$object->getId()}' LIMIT 1";

        self::query($query);

        return true;
    }

    private static function query(string $query)
    {
        if (!isset(self::$db)) {
            DB::connect();
        }

        return self::$db->query($query);
    }
}