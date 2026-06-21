<?php

namespace Gopay\GopayUi\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UninstallCommand extends Command
{
    protected $signature = 'gopay:uninstall {--force}';

    protected $description = 'Uninstall GoPay UI package';

    public function handle(): int
    {
        if (!$this->option('force')) {

            if (!$this->confirm(
                'This will delete GoPay tables and configuration. Continue?'
            )) {
                return self::FAILURE;
            }
        }

        Schema::dropIfExists('gopay');
        Schema::dropIfExists('gopay_form');

        $migrationPath = __DIR__ . '/../../database/migrations';
        $files = glob($migrationPath . '/*.php');
        $migrations = [];
        foreach ($files as $file) {
            $migrations[] = pathinfo($file, PATHINFO_FILENAME);
        }
        DB::table('migrations')
            ->whereIn('migration', $migrations)
            ->delete();

        $config = config_path('gopay.php');

        if (File::exists($config)) {
            File::delete($config);
        }

        $this->newLine();
        $this->info('✓ GoPay UI uninstalled successfully');
        $this->newLine();

        return self::SUCCESS;
    }
}
