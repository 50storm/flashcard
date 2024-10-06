<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class ResetDatabaseWithBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:reset-with-backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'バックアップを取り、データベースをリセットし、バックアップを復元します';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('データベースのバックアップを開始します...');
        
        // バックアップファイルのパス
        $backupPath = storage_path('app/backups/database_backup.sql');

        // MySQLの場合のバックアップコマンド
        $dbHost = env('DB_HOST');
        $dbPort = env('DB_PORT');
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');

        $dumpCommand = "mysqldump -h {$dbHost} -P {$dbPort} -u {$dbUser} -p'{$dbPass}' {$dbName} > {$backupPath}";

        exec($dumpCommand, $output, $returnVar);

        if ($returnVar !== 0) {
            $this->error('バックアップに失敗しました。');
            return 1;
        }

        $this->info('バックアップが完了しました。');

        // データベースをリセット
        $this->info('データベースをリセットします...');
        Artisan::call('migrate:fresh', [], $this->output);
        $this->info('マイグレーションが完了しました。');

        // バックアップを復元
        $this->info('バックアップを復元します...');

        $restoreCommand = "mysql -h {$dbHost} -P {$dbPort} -u {$dbUser} -p'{$dbPass}' {$dbName} < {$backupPath}";

        exec($restoreCommand, $output, $returnVar);

        if ($returnVar !== 0) {
            $this->error('バックアップの復元に失敗しました。');
            return 1;
        }

        $this->info('バックアップが正常に復元されました。');

        return 0;
    }
}
