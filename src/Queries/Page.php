<?php

declare(strict_types=1);

namespace Neoxenos\PhpSimpleGraphqlBlog\Queries;

use PDO;
use Neoxenos\PhpSimpleGraphqlBlog\Helpers\SwoolePdoPool as PdoPool;
use RuntimeException;
use Swoole\Exception;
use Swoole\Coroutine\Channel;
use function Neoxenos\PhpSimpleGraphqlBlog\Helpers\selectedColumnsSQL;

class Page
{
    public function pageBySlug(array $args, array $selectedFields): array
    {
        $chan = new Channel(1);
        try {
            go(function () use ($chan, $args, $selectedFields) {
                $pool = (new PdoPool())->db();
                $pdo = $pool->get();
                $colums = selectedColumnsSQL(array_keys($selectedFields, true, true));
                $statement = $pdo->prepare("SELECT " . implode(", ", $colums) . " FROM page WHERE slug = :page_slug AND type = :type AND status = :status LIMIT 1");
                if (!$statement) {
                    throw new RuntimeException('Prepare failed');
                }
                $statement->bindValue('page_slug', $args['slug'] ?? '', \PDO::PARAM_STR);
                $statement->bindValue('type', $args['type'] ?? 'page', \PDO::PARAM_STR);
                $statement->bindValue('status', $args['status'] ?? 'publish', \PDO::PARAM_STR);
                $result = $statement->execute();
                if (!$result) {
                    throw new RuntimeException('Execute failed');
                }
                $pool->put($pdo);
                //$pool->close();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                $result !== false ? $result : [];
                $chan->push($result);
            });
        } catch (Exception $e) {
            $chan->push(['error' => $e->getMessage()]);
        }
        return $chan->pop();
    }


}
