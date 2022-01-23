<?php

declare(strict_types=1);

use GraphQL\Type\Definition\ResolveInfo;
use Neoxenos\PhpSimpleGraphqlBlog\Queries\Page;

require_once __DIR__ . '/vendor/autoload.php';

if (!function_exists('createResolvers')) {

    function createResolvers(): array
    {

        $query = [
            'PageBySlug' => function (?array $root, array $args, $context, ResolveInfo $info): array {
                // var_dump($info->getFieldSelection()); selected fields to fetch: type Array
                //var_dump($info->getFieldSelection()); exit;
                return (new Page)->pageBySlug($args, $info->getFieldSelection());
            },
            'ListBlogByType' => function (?array $root, array $args, $context, ResolveInfo $info): array {
                // var_dump($info->getFieldSelection()); selected fields to fetch: type Array
                return (new Page)->pageBySlug($args, $info->getFieldSelection());
            },
            'ListBlogByCategories' => function (?array $root, array $args, $context, ResolveInfo $info): array {
                // var_dump($info->getFieldSelection()); selected fields to fetch: type Array
                return (new Page)->pageBySlug($args, $info->getFieldSelection());
            },

            'LisyTagsByPage' => function (?array $root, array $args, $context, ResolveInfo $info): array {
                // var_dump($info->getFieldSelection()); selected fields to fetch: type Array
                return (new Page)->pageBySlug($args, $info->getFieldSelection());
            },
            'Authors' => function (?array $root, array $args, $context, ResolveInfo $info): array {
                // var_dump($info->getFieldSelection()); selected fields to fetch: type Array
                return (new Page)->pageBySlug($args, $info->getFieldSelection());
            },
        ];

        $mutation = [];

        return [
            'Query'    => $query,
            'Mutation' => $mutation,
        ];
    }
}
