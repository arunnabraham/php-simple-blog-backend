<?php
declare(strict_types=1);
namespace Neoxenos\PhpSimpleGraphqlBlog;

use PDO;
use Neoxenos\PhpSimpleGraphqlBlog\Helpers\SwoolePdoPool as PdoPool;
use RuntimeException;
use Swoole\Exception;
use Swoole\Coroutine\Channel;

use function Siler\Swoole\json as json;


class Blog {
    public $result = '';
    public function listBlogs()
    {
        try {
            $limit = 1;
            $pool = (new PdoPool())->db();

            $chan = new Channel(1);
            go(function () use ($pool, $limit, $chan) {
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
                $pool->put($pdo);
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                $chan->push($result);
            });
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return json($chan->pop());
    }

    public function detailBlog(string $id, $idType = 'ref', bool $mode = false)
    {
    }

    public function updateBlog(string $id, $idType, $mode)
    {
    }
}