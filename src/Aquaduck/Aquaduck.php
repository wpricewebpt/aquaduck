<?php

namespace Webpt\Aquaduck;

use Webpt\Aquaduck\Exception\InvalidArgumentException;
use SplPriorityQueue;

class Aquaduck
{
    private $queue;

    public function __construct()
    {
        $this->queue = new SplPriorityQueue();
    }

    public function __invoke($subject, $out = null)
    {
        if ($out !== null && !is_callable($out)) {
            throw new InvalidArgumentException('Final Handler must be callable');
        }

        $done   = $out ?: new FinalHandler();
        $next   = new Next($this->queue, $done, new Handler);
        return $next($subject);
    }

    public function bind($middleware, $priority = 1)
    {
        if (!is_callable($middleware)) {
            throw new InvalidArgumentException('Middleware must be callable');
        }

        $this->queue->insert($middleware, $priority);
        return $this;
    }
}