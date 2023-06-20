<?php

require_once 'Entity.php';

class DB
{
    public function __construct(
        public readonly string $host,
        public readonly int $port,
        public readonly string $user,
        public readonly string $password,
        public readonly string $dbname,
        private mysqli $db,
    )
    {
        $db = mysqli_connect($this->host, $this->user, $this->password, $this->dbname, $this->port);
    }

    public function selectOneById(int $id, string $table)
    {

    }

    public function selectAll(Entity $object, $limit = null)
    {
        $query = 'SELECT * FROM '. $object::$table .' ORDER BY id DESC';

        if ($limit !== null) {
            $query .= ' LIMIT ' . $limit;
        }

        $result = $this->db->query($query);

        while ($row = $result->fetch_object(Entity::class)) {
            var_dump($row);
        }
    }

    public function insert(Entity $object)
    {
        $fields = get_object_vars($object);

        $columns = '';
        $values = '';

        foreach ($fields as $field => $value) {
            $columns .= $field .', ';
            $values .= "'$value', ";
        }

        $columns = rtrim($columns, ', ');
        $values = rtrim($values, ', ');

        $query = 'INSERT INTO '. $object::$table ." ($columns) VALUES ($values)";
        $this->db->query($query);
    }

    public function deleteById(Entity $class, int $id)
    {
        $query = 'UPDATE '. $class::$table .' SET deleted = true LIMIT 1';

        $this->db->query($query);
    }
}