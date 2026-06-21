<?php

namespace Gopay\GopayUi\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallCommand extends Command
{
    protected $signature = 'gopay:install';

    protected $description = 'Install GoPay UI package';

    public function handle(): int
    {
        $this->newLine();
        $this->info('Installing GoPay UI...');

        $this->call('vendor:publish', [
            '--tag' => 'config',
            '--force' => true,
            // '--provider' => \Mecxer713\GoPay\GoPayServiceProvider::class,
        ]); // config.php mexcer713/gopay-php

        $this->call('migrate');

        $this->newLine();
        $this->info('✓ GoPay UI installed successfully');
        $this->newLine();

        return self::SUCCESS;
    }
}
