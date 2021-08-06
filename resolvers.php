<?php

use Neoxenos\PhpSimpleGraphqlBlog\Queries\Page;

require_once __DIR__ . '/vendor/autoload.php';

$query = [
    'pageBySlug' => function($root, $args) {
        return (new Page)->pageBySlug($args);
    },
];

$mutation = [];

return [
    'Query'    => $query,
    'Mutation' => $mutation,
];