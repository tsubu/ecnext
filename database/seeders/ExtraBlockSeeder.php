<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * @deprecated ブロック型は resources/blocks のフォルダ + `php artisan blocks:sync` が正。
 *             共有ライブラリのデモ実体は BlockLibrarySeeder（_shared_library.json）で再構築します。
 */
class ExtraBlockSeeder extends Seeder
{
    public function run(): void
    {
        // 意図的に空 — プリセットは BlockLibrarySeeder のみを参照してください。
    }
}
