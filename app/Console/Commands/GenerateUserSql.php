<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class GenerateUserSql extends Command
{
    protected $signature = 'users:generate-sql';

    protected $description = 'Generate SQL file with hashed passwords';

    public function handle()
    {
        $csvFile = storage_path('app/newgpusernamepassword.csv');

        $handle = fopen($csvFile, 'r');

        $sql = "INSERT INTO users (district,block_name,gp_name,role,username,password,email,created_at,updated_at) VALUES\n";

        $rows = [];

        while (($data = fgetcsv($handle)) !== false) {

            $district = addslashes($data[0]);
            $block    = addslashes($data[1]);
            $gp       = addslashes($data[2]);
            $role     = addslashes($data[3]);
            $username = addslashes($data[4]);
            $password = Hash::make($data[5]);
            $email    = addslashes($data[6]);

            $rows[] = "(
                '$district',
                '$block',
                '$gp',
                '$role',
                '$username',
                '$password',
                '$email',
                NOW(),
                NOW()
            )";
        }

        fclose($handle);

        $sql .= implode(",\n", $rows) . ";";

        file_put_contents(storage_path('app/users.sql'), $sql);

        $this->info('SQL file generated successfully.');
    }
}