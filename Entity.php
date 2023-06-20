<?php

abstract class Entity
{
    public static string $table;

    public function __construct(
        private ?int $id) {}
}