<?php
declare(strict_types=1);

namespace Neoxenos\PhpSimpleGraphqlBlog\Helpers;

use Swoole\Database\PDOConfig;
use Swoole\Database\PDOPool;




class SwoolePdoPool {

    public function db(): PDOPool
    {
        $pool = new PDOPool(
            (new PDOConfig)
                ->withHost('localhost')
                ->withPassword('root')
                ->withUsername('arun')
                ->withPort(3306)
                ->withDriver('mysql')
                ->withDbname('cook_n_taste_life')
        );

        return $pool;
    }
}