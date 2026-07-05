<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminCertificateDuplicateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_a_prefilled_duplicate_certificate_form(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $source = Certificate::create([
            'certificate_number' => 'VGTL/GEM/211554',
            'issued_at' => '2026-07-05',
            'customer_name' => 'Sample Customer',
            'weight' => '25-30 GMS',
            'shape_cut' => 'ROUND BEAD',
            'dimension' => '8MM mm',
            'colour' => 'BLACK',
            'refractive_index' => 'N/A',
            'specific_gravity' => 'N/A',
            'origin' => 'INDONESIA',
            'remarks' => 'KARUNGALI BRACELET',
            'image_path' => '/images/rbtl/service-mukhi.png',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get("/rbtl/certificates/{$source->id}/duplicate");

        $response
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Certificates/Create')
                ->where('certificate.id', null)
                ->where('certificate.certificate_number', 'VGTL/GEM/211555')
                ->where('certificate.weight', '25-30 GMS')
                ->where('certificate.remarks', 'KARUNGALI BRACELET')
                ->where('duplicateSource.id', $source->id)
                ->where('duplicateSource.certificate_number', 'VGTL/GEM/211554'));
    }
}
