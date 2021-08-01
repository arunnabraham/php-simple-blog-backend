<?php declare(strict_types=1);

use GraphQL\Error\Error;
use Swoole\Http\Request;
use function Siler\GraphQL\execute;
use function Siler\GraphQL\schema;
use Siler\Route;
use function Siler\Swoole\json;
use Neoxenos\PhpSimpleGraphqlBlog\Page;
use Swoole\Http\Response;

$basedir = __DIR__;
require_once "$basedir/vendor/autoload.php";

//Coroutine Options
Swoole\Runtime::enableCoroutine();

$options = [
    'max_coroutine' => 4096,
    'stack_size' => 2 * 1024 * 1024,
    'socket_connect_timeout' => 1,
    'socket_timeout' => -1,
    'socket_read_timeout' => -1,
    'socket_write_timeout' => -1,
    'log_level' => SWOOLE_LOG_INFO,
    'hook_flags' => SWOOLE_HOOK_ALL,
    'trace_flags' => SWOOLE_TRACE_ALL,
    'dns_cache_expire' => 60,
    'dns_cache_capacity' => 1000,
    'dns_server' => '8.8.8.8',
    'display_errors' => false,
    'aio_core_worker_num' => 10,
    'aio_worker_num' => 10,
    'aio_max_wait_time' => 1,
    'aio_max_idle_time' => 1,
    'exit_condition' => function() {
        return Swoole\Coroutine::stats()['coroutine_num'] === 0;
    },
];

Swoole\Coroutine::set($options);
//GraphQL Schema


/*$handler = function (Request $request, Response $response) {
    return  json((new Page)->listBlogs());
}; */

$handler = function () {
    Route\get('/api/blog/list', [new Page(), 'listBlogs']);
    Siler\Swoole\emit('Not found', 404);
};

$port = getenv('PORT') ? intval(getenv('PORT')) : 8000;
echo "Listening on http://localhost:$port\n";
Siler\Swoole\http($handler, $port)->start();