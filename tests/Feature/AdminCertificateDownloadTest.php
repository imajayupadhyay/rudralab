<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use ZipArchive;

class AdminCertificateDownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_download_a_single_available_certificate_pdf(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $certificate = Certificate::create([
            'card_type' => Certificate::CARD_TYPE_ONE,
            'certificate_number' => 'RBTL/ONE/1001',
            'weight' => '10 GMS',
            'shape_cut' => 'Round Bead',
            'dimension' => '8MM',
            'colour' => 'Black',
            'origin' => 'India',
            'customer_name' => 'Test Customer',
            'remarks' => 'Natural Rudraksha',
            'image_path' => '/images/rbtl/service-mukhi.png',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->get("/rbtl/certificates/{$certificate->id}/download/type_1");

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', 'attachment; filename="RBTL-ONE-1001-certificate-1.pdf"');
        $this->assertStringStartsWith('%PDF', $response->getContent());
        $this->assertStringContainsString('/Count 1', $response->getContent());
        $this->assertStringContainsString('/MediaBox [0.000 0.000 450.000 322.500]', $response->getContent());
    }

    public function test_admin_cannot_download_a_certificate_type_that_record_does_not_have(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $certificate = Certificate::create([
            'card_type' => Certificate::CARD_TYPE_ONE,
            'certificate_number' => 'RBTL/ONE/1002',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get("/rbtl/certificates/{$certificate->id}/download/type_2")
            ->assertNotFound();
    }

    public function test_admin_bulk_downloads_requested_type_as_zip_and_skips_unavailable_types(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $typeOneOnly = Certificate::create([
            'card_type' => Certificate::CARD_TYPE_ONE,
            'certificate_number' => 'RBTL/ONE/2001',
            'is_active' => true,
        ]);
        $both = Certificate::create([
            'card_type' => Certificate::CARD_TYPE_BOTH,
            'certificate_number' => 'RBTL/BOTH/2002',
            'natural_faces' => 'Thirteen',
            'image_path' => '/images/rbtl/service-mukhi.png',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post('/rbtl/certificates/download', [
            'card_type' => Certificate::CARD_TYPE_TWO,
            'certificate_ids' => [$typeOneOnly->id, $both->id],
        ]);

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/zip');

        $zipPath = $response->baseResponse->getFile()->getPathname();
        $zip = new ZipArchive();

        $this->assertTrue($zip->open($zipPath));
        $this->assertSame(1, $zip->numFiles);
        $this->assertSame('RBTL-BOTH-2002-certificate-2.pdf', $zip->getNameIndex(0));
        $pdf = (string) $zip->getFromIndex(0);

        $this->assertStringStartsWith('%PDF', $pdf);
        $this->assertStringContainsString('/Count 1', $pdf);
        $this->assertStringContainsString('/MediaBox [0.000 0.000 450.000 293.684]', $pdf);
        $zip->close();

        @unlink($zipPath);
    }
}
