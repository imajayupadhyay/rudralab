<?php

namespace App\Support;

use App\Models\ContentBlock;
use Illuminate\Support\Facades\Schema;

class HomePageContent
{
    public const PAGE = 'home';

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
            'hero' => [
                'badge' => 'Rudra Beads & Gems Testing Lab',
                'title' => 'RBTL is certified',
                'highlight' => 'ISO 9001 & IAF',
                'description' => 'RBTL is a dedicated Rudra beads & gems testing lab, EGAC-accredited and ISO 9001:2015 certified, delivering reliable Rudraksha identification, Mukhi verification, X-Ray structure review, and transparent certification backed by trained experts.',
                'primary_button_text' => 'Contact Now',
                'primary_button_url' => '/contact',
                'secondary_button_text' => 'Verify a certificate',
                'secondary_button_url' => '/verify-certificate',
                'accreditation_label' => 'Accredited & Certified',
                'egac_image' => '/images/rbtl/EGAC.jpeg',
                'egac_alt' => 'EGAC Accredited Certification, International Accreditation Forum',
                'iso_image' => '/images/rbtl/iso-certified.jpeg',
                'iso_alt' => 'ISO 9001:2015 Certified Company',
            ],
            'stats' => [
                ['value' => 'RBTL', 'label' => 'Dedicated Rudra beads & gems testing lab'],
                ['value' => '5+', 'label' => 'Quality and identity checks'],
                ['value' => 'X-Ray', 'label' => 'Mukhi structure analysis'],
                ['value' => '100%', 'label' => 'Integrity & transparency'],
            ],
            'welcome' => [
                'eyebrow' => 'Welcome to RBTL',
                'title' => 'Protecting trust in sacred Rudra beads.',
                'paragraphs' => [
                    'Rudra beads carry deep spiritual and cultural significance. For many devotees and collectors, authenticity matters because every bead represents faith, discipline, and personal belief.',
                    'Natural beads can vary widely in shape, surface, texture, and Mukhi structure, so careful inspection and transparent reporting help customers make informed decisions.',
                    'At RBTL, we go beyond basic visual checks. We document bead identity, verify Mukhi features, and provide certificates that customers can validate through our internal registry.',
                ],
            ],
            'testing' => [
                'title' => 'What we test',
                'subtitle' => 'Rudra beads & gems testing and certification',
                'items' => [
                    'Rudraksha Beads & Gems Testing',
                    'Mukhi Identification',
                    'X-Ray Structure Review',
                    'Natural Origin Assessment',
                    'Certification & Report Verification',
                ],
            ],
            'vision_mission' => [
                'eyebrow' => 'Vision & Mission',
                'title' => 'Bringing clarity to Rudra bead certification.',
                'description' => 'We combine traditional respect for Rudraksha with structured laboratory inspection, clear certificate records, and a verification process that keeps every report easy to check.',
                'image' => '/images/rbtl/lab-preview.png',
                'image_alt' => 'RBTL lab preview',
                'mission_title' => 'Our Mission',
                'vision_title' => 'Our Vision',
                'mission' => [
                    'Provide accurate and dependable Rudra beads & gems certification',
                    'Make Rudraksha testing accessible to everyone',
                    'Maintain highest standards of integrity and transparency',
                    'Educate customers about authentic Rudraksha beads',
                    'Preserve trust in natural Rudra beads and spiritual products',
                ],
                'vision' => [
                    "Become India's most trusted Rudra beads & gems testing lab",
                    'Set new standards in Rudraksha certification services',
                    'Bridge traditional faith with modern scientific testing',
                    'Expand reliable bead testing services with transparent reporting',
                ],
            ],
            'services' => [
                'eyebrow' => 'Our Services',
                'title' => 'Certified with scientific rigor.',
                'items' => [
                    [
                        'title' => 'Rudra Beads & Gems Testing',
                        'desc' => 'Detailed testing for Rudraksha beads, gemstones, bead structure, surface features, and identity markers using careful laboratory procedures.',
                        'img' => '/images/rbtl/service-testing.png',
                        'link_text' => 'Learn more',
                        'link_url' => '/contact',
                    ],
                    [
                        'title' => 'Mukhi Verification',
                        'desc' => 'Mukhi counting and verification supported by visual inspection, X-Ray review, and structured certificate records.',
                        'img' => '/images/rbtl/service-mukhi.png',
                        'link_text' => 'Learn more',
                        'link_url' => '/contact',
                    ],
                    [
                        'title' => 'Certificate Registry',
                        'desc' => 'Each issued certificate can be checked through the RBTL registry so customers can confirm report details with confidence.',
                        'img' => '/images/rbtl/service-certificate.png',
                        'link_text' => 'Learn more',
                        'link_url' => '/contact',
                    ],
                ],
            ],
            'cta' => [
                'title' => 'Authenticate your Rudra beads with confidence.',
                'description' => 'Get a clear RBTL certificate, or verify a report you already hold.',
                'primary_button_text' => 'Contact Now',
                'primary_button_url' => '/contact',
                'secondary_button_text' => 'Verify Certificate',
                'secondary_button_url' => '/verify-certificate',
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
