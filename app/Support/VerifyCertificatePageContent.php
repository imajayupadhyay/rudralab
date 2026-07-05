<?php

namespace App\Support;

use App\Models\ContentBlock;
use Illuminate\Support\Facades\Schema;

class VerifyCertificatePageContent
{
    public const PAGE = 'verify_certificate';

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
                'badge' => 'Certificate Verification',
                'title' => 'Verify RBTL',
                'highlight' => 'Certificate',
                'description' => 'Enter the certificate number printed on your RBTL report to confirm authenticity and view the recorded bead details.',
            ],
            'form' => [
                'label' => 'Certificate Number',
                'placeholder' => 'e.g. VGTL/GEM/211554',
                'button_text' => 'Verify',
                'empty_message' => 'Make sure your certificate number is correct.',
                'demo_prefix' => 'Demo record available. Try',
                'demo_certificate' => 'VGTL/GEM/211554',
            ],
            'result' => [
                'verified_label' => 'Verified & Authentic',
                'issued_prefix' => 'Issued',
                'image_alt' => 'Certified Rudra bead',
                'registry_note' => 'This record is held in the RBTL Rudra Beads & Gems Testing Lab registry. Scan the QR code on the certificate preview to open this exact verification record.',
                'not_found_title' => 'No record found',
                'not_found_prefix' => "We couldn't find a certificate matching",
                'not_found_suffix' => 'Double-check the number, or reach out to our team for assistance.',
                'field_labels' => [
                    'certificate' => 'Certificate',
                    'weight' => 'Weight',
                    'shape_cut' => 'Shape/Cut',
                    'dimension' => 'Dimension',
                    'colour' => 'Colour',
                    'refractive_index' => 'Refractive Index',
                    'specific_gravity' => 'Specific Gravity',
                    'origin' => 'Origin',
                    'issued_to' => 'Issued to',
                    'remarks' => 'Remarks',
                ],
            ],
            'certificate_preview' => [
                'logo_text' => 'R',
                'brand_title' => 'RBTL Certified',
                'brand_tagline' => 'Analysis - Research - Authentication',
                'report_title' => 'Identification Report',
                'qr_alt' => 'Certificate verification QR code',
                'item_alt' => 'Certified bead item',
                'signature_name' => 'RBTL',
                'signature_label' => 'Authorised Signatory',
            ],
            'help' => [
                ['n' => '01', 't' => 'Find the number printed on your RBTL certificate'],
                ['n' => '02', 't' => 'Enter it above exactly as shown'],
                ['n' => '03', 't' => 'Confirm the details match your Rudra bead'],
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
