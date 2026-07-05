<?php

namespace Tests\Feature;

use App\Models\ContentBlock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFooterContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_footer_content(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->put('/rbtl/footer', [
            'content' => [
                'brand' => [
                    'mark' => 'RB',
                    'title' => 'RBTL Lab',
                    'description' => 'Managed footer description.',
                ],
                'navigation' => [
                    'heading' => 'Explore',
                    'links' => [
                        ['label' => 'Home', 'url' => '/'],
                        ['label' => 'Contact', 'url' => '/contact'],
                    ],
                ],
                'services' => [
                    'heading' => 'Testing',
                    'items' => [
                        ['label' => 'Mukhi Verification'],
                    ],
                ],
                'legal' => [
                    'copyright' => '2026 RBTL Lab.',
                    'tagline' => 'Managed footer tagline.',
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.footer.edit'));

        $this->assertDatabaseHas('content_blocks', [
            'page' => 'footer',
            'section' => 'brand',
            'key' => 'content',
        ]);

        $this->assertSame(
            'RBTL Lab',
            ContentBlock::where('page', 'footer')->where('section', 'brand')->first()->value['title'],
        );
    }

    public function test_footer_navigation_urls_must_be_internal(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->from('/rbtl/footer')->put('/rbtl/footer', [
            'content' => [
                'brand' => [
                    'mark' => 'R',
                    'title' => 'RBTL',
                    'description' => 'Footer description.',
                ],
                'navigation' => [
                    'heading' => 'Navigate',
                    'links' => [
                        ['label' => 'External', 'url' => 'https://example.com'],
                    ],
                ],
                'services' => [
                    'heading' => 'Services',
                    'items' => [
                        ['label' => 'Certificate Registry'],
                    ],
                ],
                'legal' => [
                    'copyright' => '2026 RBTL.',
                    'tagline' => 'Footer tagline.',
                ],
            ],
        ]);

        $response
            ->assertRedirect('/rbtl/footer')
            ->assertSessionHasErrors('content.navigation.links.0.url');
    }
}
