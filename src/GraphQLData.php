<?php

namespace Neoxenos\PhpSimpleGraphqlBlog;

use GraphQL\Error;
use Swoole\Coroutine;
use Swoole\Coroutine\Channel;
use function Siler\GraphQL\schema;
use function Siler\GraphQL\execute;

class GraphQLData {
    public function init()
    {
        $contents = \Siler\Swoole\request()->getContent();
        $args = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        if(!empty($contents))
        {
            $chan = new Channel(1);
            go(function() use ($chan){

                $graphQlSchema = Coroutine\System::readFile(__DIR__ .'/../schema/site.graphql');
                $chan->push($graphQlSchema);

            });
            $schema = schema($chan->pop(), require_once __DIR__ . '/../resolvers.php');

            return \Siler\Swoole\json(execute($schema, $args));
        }
        return \Siler\Swoole\json([], 404);
    }
}