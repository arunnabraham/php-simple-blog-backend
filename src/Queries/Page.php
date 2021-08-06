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
    public function pageBySlug(array $args)
    {
        $pool = (new PdoPool())->db();
        try {
            $chan = new Channel(1);
            go(function () use ($pool, $chan, $args) {
                $pdo = $pool->get();
                $colums = [
                    "id AS ID",  
                    "title", 
                    "main_content AS content", 
                    "meta_description AS metaDescription", 
                    "meta_keywords AS metaKeywords",
                    "slug"
                ];
                $statement = $pdo->prepare("SELECT ".implode(", ", $colums)." FROM content WHERE page_slug = :page_slug AND content_type = 'page' publish_status = 'publish'");
                if (!$statement) {
                    throw new RuntimeException('Prepare failed');
                }
                $statement->bindValue('page_slug', $args['slug'], \PDO::PARAM_STR);
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
}
