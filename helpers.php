<?php

function homologateString(string $str) : ?string
{
    $str = trim($str);

    return mb_strlen($str) ? $str : null;
}