<?php

namespace Tests\Feature;

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class CertificateVerificationResponseTest extends TestCase
{
    use RefreshDatabase;

    public function test_verification_page_is_a_non_cacheable_full_document(): void
    {
        $response = $this->get('/verify-certificate?certificate=RBTL501');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/html; charset=UTF-8');

        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));
        $this->assertContains('X-Inertia', $response->baseResponse->getVary());
        $this->assertContains('Accept-Encoding', $response->baseResponse->getVary());
    }

    public function test_inertia_verification_visit_is_forced_to_reload_instead_of_returning_json(): void
    {
        $request = Request::create('/verify-certificate?certificate=RBTL501');
        $version = app(HandleInertiaRequests::class)->version($request);

        $response = $this->withHeaders([
            'X-Inertia' => 'true',
            'X-Inertia-Version' => $version,
            'X-Requested-With' => 'XMLHttpRequest',
        ])->get('/verify-certificate?certificate=RBTL501');

        $response->assertStatus(409);
        $response->assertHeader(
            'X-Inertia-Location',
            'http://localhost/verify-certificate?certificate=RBTL501'
        );

        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));
        $this->assertSame('', $response->getContent());
    }
}
