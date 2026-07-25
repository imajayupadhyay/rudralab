<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CertificateCardTypeTest extends TestCase
{
    use RefreshDatabase;

    public function test_existing_style_records_default_to_type_one_without_requiring_new_data(): void
    {
        $certificate = Certificate::create([
            'certificate_number' => 'RBTL/OLD/1001',
            'weight' => '10 GMS',
            'is_active' => true,
        ])->fresh();

        $this->assertSame(Certificate::CARD_TYPE_ONE, $certificate->card_type);
        $this->assertTrue($certificate->showsTypeOne());
        $this->assertFalse($certificate->showsTypeTwo());

        $this->get('/verify-certificate?certificate=RBTL%2FOLD%2F1001')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('VerifyCertificate/index')
                ->where('certificate.card_type', Certificate::CARD_TYPE_ONE)
                ->where('certificate.number', 'RBTL/OLD/1001'));
    }

    public function test_admin_can_create_a_certificate_that_generates_both_card_types(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post('/rbtl/certificates', [
            'card_type' => Certificate::CARD_TYPE_BOTH,
            'certificate_number' => 'RBTL/BOTH/2001',
            'issued_at' => '2026-07-26',
            'customer_name' => 'Both Card Customer',
            'weight' => '04.527 Gm',
            'shape_cut' => 'Irregular oval / Bead',
            'dimension' => '28.93 × 24.18 × 17.46',
            'colour' => 'Brown',
            'refractive_index' => 'N/A',
            'specific_gravity' => 'N/A',
            'origin' => 'NEPAL',
            'remarks' => 'Natural Rudraksha',
            'reference_code' => 'RBTL/14',
            'issue_location' => 'NEW DELHI',
            'particulars' => 'One loose bead',
            'natural_faces' => 'Thirteen',
            'artificial_faces' => 'None',
            'test_carried_out' => 'X-Rays, Magnification',
            'xray_results' => 'X-Ray shows 13 natural compartments',
            'conclusions' => 'Results confirm natural origin',
            'genus_type' => 'ELAEOCARPUS / E. GANITRUS',
            'certificate_title' => 'NATURAL 13-MUKHI RUDRAKSHA',
            'image_path' => '/images/rbtl/service-mukhi.png',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.certificates.index'));

        $this->assertDatabaseHas('certificates', [
            'certificate_number' => 'RBTL/BOTH/2001',
            'card_type' => Certificate::CARD_TYPE_BOTH,
            'natural_faces' => 'Thirteen',
            'customer_name' => 'Both Card Customer',
        ]);

        $this->get('/verify-certificate?certificate=RBTL%2FBOTH%2F2001')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('VerifyCertificate/index')
                ->where('certificate.card_type', Certificate::CARD_TYPE_BOTH)
                ->where('certificate.type_two.natural_faces', 'Thirteen')
                ->where('certificate.type_two.certificate_title', 'NATURAL 13-MUKHI RUDRAKSHA')
                ->has('certificate.fields', 17));
    }

    public function test_type_two_only_payload_contains_its_card_data_without_type_one_fields(): void
    {
        Certificate::create([
            'card_type' => Certificate::CARD_TYPE_TWO,
            'certificate_number' => 'RBTL/TWO/3001',
            'particulars' => 'One loose bead',
            'natural_faces' => 'Five',
            'genus_type' => 'ELAEOCARPUS / E. GANITRUS',
            'is_active' => true,
        ]);

        $this->get('/verify-certificate?certificate=RBTL%2FTWO%2F3001')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('VerifyCertificate/index')
                ->where('certificate.card_type', Certificate::CARD_TYPE_TWO)
                ->where('certificate.type_two.natural_faces', 'Five')
                ->has('certificate.fields', 13)
                ->where('certificate.fields', fn ($fields) => $fields->doesntContain('k', 'Issued to')));
    }
}
