<?php

$blockLocales = array_values(array_filter(array_map('trim', explode(',', env('BLOCK_LOCALES', 'ja,en')))));

return [

    /*
    |--------------------------------------------------------------------------
    | Block instance locales (ISO 639-1, optional region)
    |--------------------------------------------------------------------------
    |
    | Comma-separated list in env `BLOCK_LOCALES` (e.g. `ja,en,fr,de,ko,zh_CN`).
    | Admin block editor shows one display-name field per code. Shared library
    | JSON may include only some keys in `name_locales`; missing locales fall
    | back via `BlockInstance::resolvedName()`.
    |
    | The first locale is the primary: seeders set `block_instances.name` from
    | `name_locales[first]` when present.
    |
    | Optional `locale_labels` below overrides human-readable labels in the UI
    | (otherwise `LanguageService::labelForLocale()` is used).
    |
    */

    'locales' => $blockLocales === [] ? ['ja', 'en'] : $blockLocales,

    /*
    |--------------------------------------------------------------------------
    | Optional display labels for block locale codes (admin UI only)
    |--------------------------------------------------------------------------
    |
    | Example: 'th' => 'ไทย', 'zh_CN' => '中文（简体）'
    |
    */

    'locale_labels' => [],

    /*
    |--------------------------------------------------------------------------
    | Folder block plugins (WordPress-style: one directory per block)
    |--------------------------------------------------------------------------
    |
    | Root directory (default `resources/blocks/`). Each immediate subfolder is
    | one block; folder name = `type_key` (must match URL-safe slug).
    |
    | Required files per folder:
    |   - block.json  — manifest: name, optional category, schema, icon, version
    |   - view.blade.php — storefront template (same $settings / $instance as core blocks)
    |
    | Optional: `lang/{locale}.json` in each block folder (one file per locale code
    | matching `BLOCK_LOCALES`, e.g. `ja.json`, `en.json`, `fr.json`).
    | Use key `name` for the localized blueprint title in admin, and any custom keys
    | for storefront copy via `block_trans('type_key', 'key', 'fallback')` in
    | `view.blade.php`. Merges with `fallback_locale` for missing keys.
    |
    | Run `php artisan blocks:sync` to upsert rows into `block_types`. The default
    | `DatabaseSeeder` runs this after CMS setup. Optional demo shared instances
    | for the library live in `resources/blocks/_shared_library.json` and are
    | loaded only by `php artisan db:seed --class=BlockLibrarySeeder` (destructive
    | wipe of block-related tables first).
    |
    | View resolution order on the storefront: active theme override → folder
    | plugin (`resources/blocks/{type_key}/view.blade.php`) → legacy fallback
    | `components/shop/blocks/{type_key}.blade.php` (optional; bundled blocks
    | live under resources/blocks).
    |
    */

    'plugins_path' => env('BLOCK_PLUGINS_PATH')
        ? base_path(env('BLOCK_PLUGINS_PATH'))
        : resource_path('blocks'),

    'plugins_manifest' => 'block.json',

    'plugins_view_entry' => 'view',

    /*
    |--------------------------------------------------------------------------
    | Adding a block without the folder plugin (legacy / theme-only)
    |--------------------------------------------------------------------------
    |
    | 1. Admin: Block Center → New Blueprint — set a unique `type_key` and schema,
    |    or insert a row into `block_types` via migration/seeder.
    | 2. Views: prefer `resources/blocks/{type_key}/view.blade.php`, or a theme file
    |    `resources/views/themes/{active_theme}/blocks/{type_key}.blade.php`, or
    |    legacy `resources/views/components/shop/blocks/{type_key}.blade.php`.
    | 3. Create Block Instances as usual; each instance has its own settings and
    |    optional `name_locales` / `settings_locales`. Layouts reference instances
    |    by id — types and instances evolve independently.
    |
    */
];
