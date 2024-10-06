<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Exception;

class BackupMigrateRestore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup-migrate-restore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'バックアップを作成し、migrate:fresh を実行し、バックアップデータを復元します';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('データベースバックアップ、マイグレーション、データ復元を開始します。');

        // バックアップファイル名の作成
        $timestamp = now()->format('Ymd_His');
        $backupFile = "backups/database_backup_{$timestamp}.sql";

        // バックアップディレクトリの確認・作成
        if (!Storage::exists('backups')) {
            Storage::makeDirectory('backups');
            $this->info('バックアップディレクトリを作成しました: storage/app/backups');
        }

        try {
            // データベースのバックアップ
            $this->info('データベースのバックアップを作成中...');
            $sqlDump = $this->generateSqlDump();
            Storage::put($backupFile, $sqlDump);
            $this->info("バックアップが完了しました: storage/app/{$backupFile}");

            // マイグレーションのリセットと再実行
            $this->info('マイグレーションをリセットして再実行します...');
            Artisan::call('migrate:fresh', [
                '--force' => true, // 本番環境でも実行できるようにする場合
            ]);
            $this->info('マイグレーションが完了しました。');

            // データの復元
            $this->info('バックアップからデータを復元します...');
            $this->restoreSqlDump(Storage::get($backupFile));
            $this->info('データの復元が完了しました。');

            $this->info('全ての処理が正常に完了しました。');

        } catch (Exception $e) {
            $this->error('エラーが発生しました: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * データベースの全テーブルをSELECT~INSERT形式でエクスポートする
     *
     * @return string SQLダンプ
     */
    protected function generateSqlDump()
    {
        $databaseName = config('database.connections.' . config('database.default') . '.database');
        $tables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();

        $sqlDump = "-- データベースバックアップ\n";
        $sqlDump .= "-- データベース名: {$databaseName}\n";
        $sqlDump .= "-- バックアップ日時: " . now()->toDateTimeString() . "\n\n";

        foreach ($tables as $table) {
            // テーブル構造のエクスポート
            $createTable = DB::select("SHOW CREATE TABLE `{$table}`")[0]->{'Create Table'};
            $sqlDump .= "-- テーブル構造: `{$table}`\n";
            $sqlDump .= "DROP TABLE IF EXISTS `{$table}`;\n";
            $sqlDump .= "{$createTable};\n\n";

            // テーブルデータのエクスポート
            $this->info("テーブルデータをエクスポート中: {$table}");
            $rows = DB::table($table)->get();

            if ($rows->isEmpty()) {
                continue; // データがない場合はスキップ
            }

            $columns = array_map(function($column) {
                return "`" . $column . "`";
            }, array_keys((array) $rows->first()));
            $columnsList = implode(", ", $columns);

            $sqlDump .= "-- データ: `{$table}`\n";
            foreach ($rows as $row) {
                $values = array_map(function($value) {
                    if (is_null($value)) {
                        return "NULL";
                    }
                    return "'" . addslashes($value) . "'";
                }, (array) $row);
                $valuesList = implode(", ", $values);
                $sqlDump .= "INSERT INTO `{$table}` ({$columnsList}) VALUES ({$valuesList});\n";
            }
            $sqlDump .= "\n";
        }

        return $sqlDump;
    }

    /**
     * SQLダンプをデータベースに復元する
     *
     * @param string $sqlDump
     */
    protected function restoreSqlDump($sqlDump)
    {
        DB::unprepared($sqlDump);
    }
}
