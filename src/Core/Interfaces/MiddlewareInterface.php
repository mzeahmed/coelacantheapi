<?php

declare(strict_types=1);

namespace App\Core\Interfaces;

use App\Core\Http\Message\Request;

interface MiddlewareInterface
{
    public function handle(Request $request, callable $next);
}
