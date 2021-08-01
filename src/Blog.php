<?php
declare(strict_types=1);
namespace Neoxenos\PhpSimpleGraphqlBlog;

use Swoole\Coroutine\{MySQL};
use Swoole\Coroutine as Co;



class Blog {
    public function listBlogs(string $listBy='categories', int $limit=10, int $page=1)
    {
    }

    public function detailBlog(string $id ,$idType='ref', bool $mode=false)
    {

    }

    public function updateBlog(string $id, $idType, $mode)
    {
    }
}