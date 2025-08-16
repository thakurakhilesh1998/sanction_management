<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = storage_path('app/public/users.csv');
        if(File::exists($filePath))
        {
            $filecontents=File::get($filePath);
            $lines=explode("\n",trim($filecontents));
            foreach($lines as $line)
            {
                if(!empty($line))
                {
                    $columns=str_getcsv($line);
                    $district = !empty($columns[1]) ? $columns[1] : null;
                    $blockName = !empty($columns[2]) ? $columns[2] : null;
                    $gpName = !empty($columns[3]) ? $columns[3] : null;
                    $role = !empty($columns[4]) ? $columns[4] : null;
                    $zone = !empty($columns[5]) ? $columns[5] : null;
                    User::create([
                        "district"=>$district,
                        "block_name"=>$blockName,
                        "gp_name"=>$gpName,
                        "role"=>$role,
                        "zone"=>$zone,
                        "username"=>$columns['6'],
                        "password"=>Hash::make($columns['7']),
                        "email"=>$columns['8'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            $this->command->info('CSV data imported successfully.');
        }
        else 
        {
            $this->command->info('CSV file not found.');
        }
      
            
        
    }
}
