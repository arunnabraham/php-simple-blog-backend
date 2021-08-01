<?php

declare(strict_types=1);

namespace Neoxenos\PhpSimpleGraphqlBlog;

use PDO;
use RuntimeException;
use Swoole\Coroutine\MySQL;
use Swoole\Coroutine as Co;
use Swoole\Database\PDOPool;
use Swoole\Database\PDOConfig;
use Swoole\Exception;

use function Siler\Swoole\response;

const N = 5;

class Page
{
    public $result = '';
    public function listBlogs(string $listBy = 'categories', int $limit = 10, int $page = 1)
    {

        try {
            $pool = new PDOPool(
                (new PDOConfig)
                    ->withHost('localhost')
                    ->withPassword('root')
                    ->withUsername('arun')
                    ->withPort(3306)
                    ->withDriver('mysql')
                    ->withDbname('cook_n_taste_life')
            );

            Co\run(function () use ($pool, $limit) {
                go(function () use ($pool, $limit) {
                    $pdo = $pool->get();
                    $statement = $pdo->prepare("SELECT * FROM content LIMIT :limit");
                    if (!$statement) {
                        throw new RuntimeException('Prepare failed');
                    }
                    $statement->bindValue('limit', $limit, \PDO::PARAM_INT);
                    $result = $statement->execute();
                    if (!$result) {
                        throw new RuntimeException('Execute failed');
                    }
                    $result = $statement->fetch(PDO::FETCH_ASSOC);
                    $pool->put($pdo);
                    $this->result = $result;
                });
            });
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return $this->result; //$pool->get();

    }

    public function detailBlog(string $id, $idType = 'ref', bool $mode = false)
    {
    }

    public function updateBlog(string $id, $idType, $mode)
    {
    }
}
