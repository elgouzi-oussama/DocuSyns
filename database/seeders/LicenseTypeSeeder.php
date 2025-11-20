<?php

namespace Database\Seeders;

use App\Models\LicensesType;
use Illuminate\Database\Seeder;

class LicenseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                '_name' => 'Basic',
                'features' => json_encode([
                    'users' => 3,
                    'admins' => 1,
                    'storage' => '1GB',
                ]),
                'akaeay_' => 'Grade C',
            ],
            [
                '_name' => 'Pro',
                'features' => json_encode([
                    'users' => 8,
                    'admins' => 2,
                    'storage' => '5GB',
                ]),
                'akaeay_' => 'Grade B',
            ],
            [
                '_name' => 'Enterprise',
                'features' => json_encode([
                    'users' => 22,
                    'admins' => 3,
                    'storage' => '10GB',
                ]),
                'akaeay_' => 'Grade A',
            ],
        ];

        foreach ($types as $type) {
            LicensesType::updateOrCreate(['_name' => $type['_name']], $type);
        }
    }
}
