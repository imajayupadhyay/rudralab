<?php

namespace Tests\Unit;

use App\Models\Certificate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CertificateNumberTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_advances_the_trailing_certificate_number_and_skips_used_numbers(): void
    {
        Certificate::create(['certificate_number' => 'VGTL/GEM/211554']);
        Certificate::create(['certificate_number' => 'VGTL/GEM/211555']);

        $this->assertSame('VGTL/GEM/211556', Certificate::nextDuplicateNumber('VGTL/GEM/211554'));
    }

    public function test_it_preserves_numeric_padding(): void
    {
        Certificate::create(['certificate_number' => 'RBTL/009']);

        $this->assertSame('RBTL/010', Certificate::nextDuplicateNumber('RBTL/009'));
    }

    public function test_it_appends_a_number_when_the_certificate_has_no_trailing_digits(): void
    {
        Certificate::create(['certificate_number' => 'ARTICLE-1']);

        $this->assertSame('ARTICLE-2', Certificate::nextDuplicateNumber('ARTICLE'));
    }
}
