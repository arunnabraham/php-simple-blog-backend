<?php
declare(strict_types=1);

namespace Neoxenos\PhpSimpleGraphqlBlog\Helpers;

use Swoole\Database\PDOConfig;
use Swoole\Database\PDOPool;

use function Siler\Dotenv\env;

class SwoolePdoPool {

    public function db(): PDOPool
    {
        $pool = new PDOPool(
            (new PDOConfig)
                ->withHost(env('DB_HOST'))
                ->withPassword(env('DB_PASSWORD'))
                ->withUsername(env('DB_USERNAME'))
                ->withPort(intval(env('DB_PORT')))
                ->withDriver(env('DB_DRIVER'))
                ->withDbname(env('DB_NAME'))
        );

        return $pool;
    }
}