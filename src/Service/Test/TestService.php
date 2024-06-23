<?php
declare(strict_types=1);

namespace App\Service\Test;

final class TestService
{
    /**
     * @return array<string, string>
     */
    public function test(): array
    {
        return [
            'message' => 'Welcome to your new service!',
            'path' => 'src/Service/Test/TestService.php',
        ];
    }
}
