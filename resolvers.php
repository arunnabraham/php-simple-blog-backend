<?php
declare(strict_types=1);

use Neoxenos\PhpSimpleGraphqlBlog\Queries\Page;

require_once __DIR__ . '/vendor/autoload.php';

if (!function_exists('createResolvers')) {

    function createResolvers(): array
    {

        $query = [
            'pageBySlug' => function (?array $root, array $args) {
                return (new Page)->pageBySlug($args);
            }
        ];

        $mutation = [];

        return [
            'Query'    => $query,
            'Mutation' => $mutation,
        ];
    }
}
