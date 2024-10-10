<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'データベースのテーブルデータをバックアップします';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('データベースのバックアップを開始します。');

        // バックアップファイル名の作成
        $timestamp = now()->format('Ymd_His');
        $backupFile = "database_backup_{$timestamp}.sql";

        try {
            // データベースのバックアップ
            $this->info('データベースのバックアップを作成中...');
            $sqlDump = $this->generateSqlDump();
            Storage::disk('local')->put($backupFile, $sqlDump);
            $this->info("バックアップが完了しました: {$backupFile}");

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

        // SHOW TABLESでテーブル名を取得
        $tables = DB::select('SHOW TABLES');
        $tableKey = 'Tables_in_' . $databaseName;

        $sqlDump = "-- データベースバックアップ\n";
        $sqlDump .= "-- データベース名: {$databaseName}\n";
        $sqlDump .= "-- バックアップ日時: " . now()->toDateTimeString() . "\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;

            // テーブルデータのエクスポート
            $this->info("テーブルデータをエクスポート中: {$tableName}");
            $rows = DB::table($tableName)->get();

            if ($rows->isEmpty()) {
                continue; // データがない場合はスキップ
            }

            $columns = array_map(function($column) {
                return "`" . $column . "`";
            }, array_keys((array) $rows->first()));
            $columnsList = implode(", ", $columns);

            $sqlDump .= "-- データ: `{$tableName}`\n";
            foreach ($rows as $row) {
                $values = array_map(function($value) {
                    if (is_null($value)) {
                        return "NULL";
                    }
                    return "'" . addslashes($value) . "'";
                }, (array) $row);
                $valuesList = implode(", ", $values);
                $sqlDump .= "INSERT INTO `{$tableName}` ({$columnsList}) VALUES ({$valuesList});\n";
            }
            $sqlDump .= "\n";
        }

        return $sqlDump;
    }
}
