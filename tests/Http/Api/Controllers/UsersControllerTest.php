<?php

declare(strict_types=1);

use App\Services\UserService;
use PHPUnit\Framework\TestCase;
use App\Core\Http\Message\Request;
use Psr\Http\Message\UriInterface;
use App\Http\Controllers\Api\UserController;

class UsersControllerTest extends TestCase
{
    public function testShow()
    {
        $userService = $this->createMock(UserService::class);
        $userService->method('getUser')->willReturn(null);

        $controller = new UserController($userService);

        $uri = $this->createMock(UriInterface::class);
        $uri->method('getPath')->willReturn('/users/1');

        $request = $this->createMock(Request::class);
        $request->method('getUri')->willReturn($uri);

        ob_start();
        $controller->show($request);
        $output = ob_get_clean();

        $this->assertStringContainsString('User not found', $output);
        $this->assertEquals(http_response_code(), 404);
    }
}
