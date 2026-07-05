<?php

namespace App\Support;

use App\Models\ContentBlock;
use Illuminate\Support\Facades\Schema;

class ContactPageContent
{
    public const PAGE = 'contact';

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
                'badge' => 'Get in Touch',
                'title' => "Let's authenticate",
                'highlight' => 'your Rudra bead.',
                'description' => 'RBTL helps customers verify Rudraksha beads through careful inspection, Mukhi verification, X-Ray supported review, and clear certificate records.',
            ],
            'info' => [
                [
                    'icon' => 'Location',
                    'label' => 'Lab Location',
                    'value' => 'Shiv Shakti Traders, near main Shaheed Udham Chowk, Guhla Road, Cheeka, Haryana',
                ],
                [
                    'icon' => 'Phone',
                    'label' => 'Call Us',
                    'value' => '+91 87085 40840'."\n".'+91 80598 72000',
                ],
                [
                    'icon' => 'Email',
                    'label' => 'Email Us',
                    'value' => 'info@rbtl.test',
                ],
                [
                    'icon' => 'Hours',
                    'label' => 'Working Hours',
                    'value' => 'Mon - Sat  10:00am - 5:00pm'."\n".'Sun  11:00am - 4:00pm',
                ],
            ],
            'address_card' => [
                'mark' => 'R',
                'label' => 'RBTL Lab Address',
                'address' => 'Shiv Shakti Traders, near main Shaheed Udham Chowk, Guhla Road, Cheeka, Haryana',
            ],
            'form' => [
                'title' => 'Send us a message',
                'description' => 'Fill in your details and the RBTL team will get back to you shortly.',
                'success_message' => "Thank you, your message has been noted. We'll reach out soon.",
                'name_label' => 'Name',
                'name_placeholder' => 'Your name',
                'phone_label' => 'Phone',
                'phone_placeholder' => '+91 ...',
                'email_label' => 'Email',
                'email_placeholder' => 'you@example.com',
                'message_label' => 'Message',
                'message_placeholder' => "Tell us about the Rudra bead you'd like certified...",
                'button_text' => 'Send Message',
            ],
            'hours' => [
                'icon' => 'Clock',
                'label' => 'Working Hours',
                'text' => 'Mon-Sat 10:00-17:00  -  Sun 11:00-16:00',
                'button_text' => 'Call +91 87085 40840',
                'button_url' => '#form',
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
