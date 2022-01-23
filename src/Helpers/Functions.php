<?php

declare(strict_types=1);

namespace Neoxenos\PhpSimpleGraphqlBlog\Helpers;

function selectedColumnsSQL(array $colums): array
{
    return array_map(fn ($field) => convertFieldsToSQLColumn($field), $colums);
}

function convertFieldsToSQLColumn(string $str): string
{
    return implode('', array_map(function ($ch) {
        return (mb_strtoupper($ch) === $ch ? '_' . mb_strtolower($ch) : $ch);
    }, str_split($str))) . ' ' . $str;
}
