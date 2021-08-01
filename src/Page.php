<?php

declare(strict_types=1);

namespace Neoxenos\PhpSimpleGraphqlBlog;

use PDO;
use Neoxenos\PhpSimpleGraphqlBlog\Helpers\SwoolePdoPool as PdoPool;
use RuntimeException;
use Swoole\Coroutine\MySQL;
use Swoole\Coroutine as Co;
use Swoole\Exception;
use Swoole\Coroutine\Channel;
use Swoole\Http\Request;
use Siler\Swoole as Sw;
use Swoole\FastCGI\Request as FastCGIRequest;

use function Siler\Swoole\json as json;

const N = 5;

class Page
{
    public $result = '';
    public function listBlogs()
    {
        var_dump(Sw\request()->get);
        return json($this->list(1));
    }

    public function detailBlog(string $id, $idType = 'ref', bool $mode = false)
    {
    }

    public function updateBlog(string $id, $idType, $mode)
    {
    }


    private function list($req)
    {

        try {
            $limit = $req;
            $pool = (new PdoPool())->db();

            $chan = new Channel(1);

//            Co\run(function () use ($pool, $limit, $chan) {
//               go(function () use ($pool, $limit, $chan) {
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
                    $chan->push($result);
//                });
//            });
           
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return $chan->pop();//$this->result; //$pool->get();
    }
}
