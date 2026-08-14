<?php

namespace Database\Seeders;

use App\Models\Opd;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OpdUserSeeder extends Seeder
{

    /**
     * Membuat akun user untuk Admin dan seluruh OPD.
     */
    public function run(): void
    {

        // ─── Akun Admin ───────────────────────────────────────────
        // 1. Create Admin account
        User::firstOrCreate(
            ['email' => 'admin@kmc.go.id'],
            [
                'name' => 'Admin',
                'username' => 'Admin',
                'password' => Hash::make('000000'),
                'role' => 'admin',
            ]
        );


        // ─── Akun OPD (1 user per OPD) ───────────────────────────────────

        $opdList = Opd::all();

        foreach ($opdList as $opd) {

            // 2. Create OPD account
            User::firstOrCreate(
                ['username' => Str::slug($opd->name)],
                [
                    'name' => $opd->name,
                    'email' => Str::slug($opd->name) . '@kmc.go.id',
                    'password' => Hash::make('000000'),
                    'role' => 'opd',
                    'opd_id' => $opd->id,
                ]
            );

        }

    }

}
