<?php declare(strict_types=1);

namespace App;

use Swoole\Http\Request;
use function Siler\Swoole\http;
use function Siler\Swoole\json;

$basedir = __DIR__;
require_once "$basedir/vendor/autoload.php";

$handler = function (Request $request) {
    json('It works');
};

$port = getenv('PORT') ? intval(getenv('PORT')) : 8000;
echo "Listening on http://localhost:$port\n";
http($handler, $port)->start();