<?php

namespace App\Support;

use App\Models\ContentBlock;
use Illuminate\Support\Facades\Schema;

class FooterContent
{
    public const PAGE = 'footer';

    /**
     * @return array<string, mixed>
     */
    public static function get(): array
    {
        if (! Schema::hasTable('content_blocks')) {
            return self::defaults();
        }

        self::ensureDefaults();

        $blocks = ContentBlock::query()
            ->where('page', self::PAGE)
            ->where('is_active', true)
            ->get()
            ->keyBy('section');

        return collect(self::defaults())
            ->mapWithKeys(function (array $default, string $section) use ($blocks): array {
                $stored = $blocks->get($section)?->value ?? [];

                return [$section => self::mergeSection($default, $stored)];
            })
            ->all();
    }

    /**
     * @param  array<string, mixed>  $content
     */
    public static function save(array $content): void
    {
        if (! Schema::hasTable('content_blocks')) {
            return;
        }

        foreach (self::defaults() as $section => $default) {
            $value = self::mergeSection($default, $content[$section] ?? []);

            ContentBlock::updateOrCreate(
                [
                    'page' => self::PAGE,
                    'section' => $section,
                    'key' => 'content',
                ],
                [
                    'type' => 'group',
                    'value' => $value,
                    'is_active' => true,
                ],
            );
        }
    }

    public static function ensureDefaults(): void
    {
        if (! Schema::hasTable('content_blocks')) {
            return;
        }

        foreach (self::defaults() as $section => $value) {
            ContentBlock::firstOrCreate(
                [
                    'page' => self::PAGE,
                    'section' => $section,
                    'key' => 'content',
                ],
                [
                    'type' => 'group',
                    'value' => $value,
                    'is_active' => true,
                ],
            );
        }
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return [
            'brand' => [
                'mark' => 'R',
                'title' => 'RBTL Rudra Beads & Gems Testing Lab',
                'description' => 'A dedicated Rudra beads & gems testing lab safeguarding authenticity, trust, and clear certification records.',
            ],
            'navigation' => [
                'heading' => 'Navigate',
                'links' => [
                    ['label' => 'Home', 'url' => '/'],
                    ['label' => 'Verify Certificate', 'url' => '/verify-certificate'],
                    ['label' => 'Contact Us', 'url' => '/contact'],
                ],
            ],
            'services' => [
                'heading' => 'Services',
                'items' => [
                    ['label' => 'Rudra Beads & Gems Testing'],
                    ['label' => 'Mukhi Verification'],
                    ['label' => 'Certificate Registry'],
                ],
            ],
            'legal' => [
                'copyright' => '2026 RBTL Rudra Beads & Gems Testing Lab. All rights reserved.',
                'tagline' => 'Authentic beads. Transparent testing.',
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $default
     * @param  array<string, mixed>  $stored
     * @return array<string, mixed>
     */
    private static function mergeSection(array $default, array $stored): array
    {
        foreach ($default as $key => $value) {
            if (! array_key_exists($key, $stored) || $stored[$key] === null) {
                $stored[$key] = $value;
                continue;
            }

            if (is_array($value) && self::isAssoc($value) && is_array($stored[$key])) {
                $stored[$key] = self::mergeSection($value, $stored[$key]);
            }
        }

        return $stored;
    }

    /**
     * @param  array<mixed>  $array
     */
    private static function isAssoc(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }
}
