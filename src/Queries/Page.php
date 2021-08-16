<?php

declare(strict_types=1);

namespace Neoxenos\PhpSimpleGraphqlBlog\Queries;

use PDO;
use Neoxenos\PhpSimpleGraphqlBlog\Helpers\SwoolePdoPool as PdoPool;
use RuntimeException;
use Swoole\Exception;
use Swoole\Coroutine\Channel;

use function Siler\Swoole\json as json;
use \Siler\Swoole as SilerSwoole;

class Page
{
    public function pageBySlug(array $args): array
    {
        try {
            $chan = new Channel(1);
            go(function () use ($chan, $args) {
                $pool = (new PdoPool())->db();
                $pdo = $pool->get();
                $colums = [
                    "id as ID",  
                    "title", 
                    "content", 
                    "meta_description AS metaDescription", 
                    "meta_keywords AS metaKeywords",
                    "slug"
                ];
                $statement = $pdo->prepare("SELECT ".implode(", ", $colums)." FROM page WHERE slug = :page_slug AND type = :type AND status = :status LIMIT 1");
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
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                $result !== false ? $result : []; 
                $chan->push($result ?? []);
            });
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
        return $chan->pop();
    }
}
