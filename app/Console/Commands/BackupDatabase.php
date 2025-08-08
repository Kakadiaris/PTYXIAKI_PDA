<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Back up';

    public function handle()
    {
        // Στοιχεία DB από .env
        $dbHost = env('DB_HOST', '127.0.0.1');
        $dbPort = env('DB_PORT', '3306');
        $dbName = env('DB_DATABASE', 'pda_app');
        $dbUser = env('DB_USERNAME', 'root');
        $dbPass = env('DB_PASSWORD', '');

        // Ο φάκελος που θα σώζουμε τα backups
        $backupPath = storage_path('app/backups');
        File::ensureDirectoryExists($backupPath);

        // Όνομα αρχείου με ημερομηνία
        $fileName = $dbName . '_backup_' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';
        $filePath = $backupPath . '/' . $fileName;

        // ΠΛΗΡΕΣ PATH στο mysqldump
        $mysqldumpPath = 'C:\xampp\mysql\bin\mysqldump.exe';

        // extra flags
        $flags = '--single-transaction --quick --routines --triggers --events';
        // Εντολή mysqldump
        $command = sprintf(
            '"%s" --host=%s --port=%s --user=%s --password=%s %s %s --result-file="%s"',
            $mysqldumpPath,
            escapeshellarg($dbHost),
            escapeshellarg($dbPort),
            escapeshellarg($dbUser),
            escapeshellarg($dbPass),
            $flags,
            escapeshellarg($dbName),
            $filePath
        );

        // Εκτέλεση
        system($command, $returnVar);

        if ($returnVar === 0) {
            $this->info("Backup δημιουργήθηκε επιτυχώς {$filePath}");
        } else {
            $this->error("Backup αποτυχία!");
        }
    }
}
