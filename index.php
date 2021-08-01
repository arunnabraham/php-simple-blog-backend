<?php declare(strict_types=1);

use GraphQL\Error\Error;
use Swoole\Http\Request;
use Swoole\Runtime;
use Swoole\Coroutine;
use Swoole\Coroutine\Server\Connection;

use function Co\go;
use Swoole\Coroutine\Server;
use function Siler\GraphQL\execute;
use function Siler\GraphQL\schema;
use function Siler\Swoole\http;
use function Siler\Swoole\json;
use Neoxenos\PhpSimpleGraphqlBlog\Page;
use Swoole\Http\Response;

$basedir = __DIR__;
require_once "$basedir/vendor/autoload.php";

//Coroutine Options
 Runtime::enableCoroutine();

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


$handler = function (Request $request, Response $response) {
    return  json((new Page)->listBlogs());
};

$port = getenv('PORT') ? intval(getenv('PORT')) : 8000;
echo "Listening on http://localhost:$port\n";
http($handler, $port)->start();

/*go(function()
{

    $server = new Server('localhost', 8000, false);
    $server->handle(function (Connection $conn) {
        while('' !== $data = $conn->recv()) {
            $json = json_decode($data, true);
            if(is_array($json) && 'hello' === $json['data']) {
                $conn->send("world\n");
            }
        }
        echo 'disconnected', PHP_EOL;
    });
    $server->start();
});
*/