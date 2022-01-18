<?php

namespace Neoxenos\PhpSimpleGraphqlBlog;

use Swoole\Coroutine;
use Swoole\Coroutine\Channel;

use function Siler\GraphQL\schema;
use function Siler\GraphQL\execute;
use function Siler\Swoole\json;

class GraphQLData
{
    public function init()
    {
        $contents = \Siler\Swoole\request()->getContent();
        //return json(json_decode($contents));
        if (!empty($contents)) {

            $chan = new Channel(1);

            go(function () use ($chan, $contents) {
                $args = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
                $typeDefs = Coroutine\System::readFile(\GRAPHQL_SCHEMA);
                $chan->push(execute(schema($typeDefs, createResolvers()), $args));
            });


            return json($chan->pop());
        }

        return json([], 404);
    }
}