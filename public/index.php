<?php
declare(strict_types=1);

use App\Kernel;

if (!file_exists('/application/var/setup_complete')) {
    exit('Please run setup script first: scripts/setup.sh');
}

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

return static fn (array $context) => new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
