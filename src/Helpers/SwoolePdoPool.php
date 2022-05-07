<?php
declare(strict_types=1);

namespace Neoxenos\PhpSimpleGraphqlBlog\Helpers;

use Swoole\Database\PDOConfig;
use Swoole\Database\PDOPool;
class SwoolePdoPool {

    public function db(): PDOPool

    {
        var_dump($_ENV);
        $pool = new PDOPool(
            (new PDOConfig)
                ->withHost($_ENV['PDO_DB_HOST'])
                ->withPassword($_ENV['PDO_DB_PASSWORD'])
                ->withUsername($_ENV['PDO_DB_USERNAME'])
                ->withPort($_ENV['PDO_DB_PORT'])
                ->withDriver($_ENV['PDO_DRIVER'])
                ->withDbname($_ENV['PDO_DB_NAME'])
        );

        return $pool;
    }
}