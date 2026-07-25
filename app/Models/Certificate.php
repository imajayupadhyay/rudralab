<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Certificate extends Model
{
    use SoftDeletes;

    public const CARD_TYPE_ONE = 'type_1';

    public const CARD_TYPE_TWO = 'type_2';

    public const CARD_TYPE_BOTH = 'both';

    protected $fillable = [
        'certificate_number',
        'normalized_certificate_number',
        'issued_at',
        'customer_name',
        'weight',
        'shape_cut',
        'dimension',
        'colour',
        'refractive_index',
        'specific_gravity',
        'origin',
        'remarks',
        'image_path',
        'extra_fields',
        'card_type',
        'reference_code',
        'issue_location',
        'particulars',
        'natural_faces',
        'artificial_faces',
        'test_carried_out',
        'xray_results',
        'conclusions',
        'genus_type',
        'certificate_title',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'date',
            'extra_fields' => 'array',
            'is_active' => 'boolean',
        ];
    }

    protected function certificateNumber(): Attribute
    {
        return Attribute::make(
            set: fn (string $value): string => trim($value),
        );
    }

    public static function booted(): void
    {
        static::saving(function (Certificate $certificate): void {
            $certificate->normalized_certificate_number = self::normalizeNumber($certificate->certificate_number);
        });
    }

    public static function normalizeNumber(string $value): string
    {
        return str($value)->trim()->upper()->toString();
    }

    public static function emptyFormData(): array
    {
        return [
            'id' => null,
            'card_type' => self::CARD_TYPE_ONE,
            'certificate_number' => '',
            'issued_at' => '',
            'customer_name' => '',
            'weight' => '',
            'shape_cut' => '',
            'dimension' => '',
            'colour' => '',
            'refractive_index' => 'N/A',
            'specific_gravity' => 'N/A',
            'origin' => '',
            'remarks' => '',
            'reference_code' => 'RBTL/14',
            'issue_location' => 'NEW DELHI',
            'particulars' => 'One loose bead',
            'natural_faces' => '',
            'artificial_faces' => 'None',
            'test_carried_out' => 'X-Rays, Magnification',
            'xray_results' => '',
            'conclusions' => 'Results confirm natural origin',
            'genus_type' => 'ELAEOCARPUS / E. GANITRUS',
            'certificate_title' => 'NATURAL 13-MUKHI RUDRAKSHA',
            'image_path' => '/images/rbtl/service-mukhi.png',
            'image_url' => '/images/rbtl/service-mukhi.png',
            'is_active' => true,
        ];
    }

    public static function nextDuplicateNumber(string $certificateNumber): string
    {
        $certificateNumber = trim($certificateNumber);

        if (preg_match('/^(.*?)(\d+)$/', $certificateNumber, $matches)) {
            $prefix = $matches[1];
            $number = (int) $matches[2];
            $padding = strlen($matches[2]);
        } else {
            $prefix = str($certificateNumber)->limit(78, '')->toString().'-';
            $number = 0;
            $padding = 1;
        }

        do {
            $number++;
            $nextNumber = $prefix.str_pad((string) $number, $padding, '0', STR_PAD_LEFT);
        } while (self::withTrashed()->where('normalized_certificate_number', self::normalizeNumber($nextNumber))->exists());

        return $nextNumber;
    }

    public function duplicateFormData(): array
    {
        return [
            ...$this->formData(),
            'id' => null,
            'certificate_number' => self::nextDuplicateNumber($this->certificate_number),
        ];
    }

    public function formData(): array
    {
        return [
            'id' => $this->id,
            'card_type' => $this->card_type ?: self::CARD_TYPE_ONE,
            'certificate_number' => $this->certificate_number,
            'issued_at' => $this->issued_at?->format('Y-m-d') ?? '',
            'customer_name' => $this->customer_name ?? '',
            'weight' => $this->weight ?? '',
            'shape_cut' => $this->shape_cut ?? '',
            'dimension' => $this->dimension ?? '',
            'colour' => $this->colour ?? '',
            'refractive_index' => $this->refractive_index ?? '',
            'specific_gravity' => $this->specific_gravity ?? '',
            'origin' => $this->origin ?? '',
            'remarks' => $this->remarks ?? '',
            'reference_code' => $this->reference_code ?? '',
            'issue_location' => $this->issue_location ?? '',
            'particulars' => $this->particulars ?? '',
            'natural_faces' => $this->natural_faces ?? '',
            'artificial_faces' => $this->artificial_faces ?? '',
            'test_carried_out' => $this->test_carried_out ?? '',
            'xray_results' => $this->xray_results ?? '',
            'conclusions' => $this->conclusions ?? '',
            'genus_type' => $this->genus_type ?? '',
            'certificate_title' => $this->certificate_title ?? '',
            'image_path' => $this->image_path ?? '',
            'image_url' => $this->imageUrl(),
            'is_active' => $this->is_active,
        ];
    }

    public function verificationPayload(): array
    {
        $fields = [
            ['k' => 'Certificate', 'v' => $this->certificate_number],
            ['k' => 'Weight', 'v' => $this->weight ?: 'N/A'],
            ['k' => 'Shape/Cut', 'v' => $this->shape_cut ?: 'N/A'],
            ['k' => 'Dimension', 'v' => $this->dimension ?: 'N/A'],
            ['k' => 'Colour', 'v' => $this->colour ?: 'N/A'],
        ];

        if ($this->showsTypeOne()) {
            array_push($fields,
                ['k' => 'Refractive Index', 'v' => $this->refractive_index ?: 'N/A'],
                ['k' => 'Specific Gravity', 'v' => $this->specific_gravity ?: 'N/A'],
            );
        }

        $fields[] = ['k' => 'Origin', 'v' => $this->origin ?: 'N/A'];

        if ($this->showsTypeOne()) {
            array_push($fields,
                ['k' => 'Issued to', 'v' => $this->customer_name ?: 'N/A'],
                ['k' => 'Remarks', 'v' => $this->remarks ?: 'N/A'],
            );
        }

        if ($this->showsTypeTwo()) {
            array_push($fields,
                ['k' => 'Particulars', 'v' => $this->particulars ?: 'N/A'],
                ['k' => 'Natural Faces', 'v' => $this->natural_faces ?: 'N/A'],
                ['k' => 'Artificial Faces', 'v' => $this->artificial_faces ?: 'N/A'],
                ['k' => 'Test Carried Out', 'v' => $this->test_carried_out ?: 'N/A'],
                ['k' => 'X-Ray Results', 'v' => $this->xray_results ?: 'N/A'],
                ['k' => 'Conclusions', 'v' => $this->conclusions ?: 'N/A'],
                ['k' => 'Genus / Type', 'v' => $this->genus_type ?: 'N/A'],
            );
        }

        return [
            'number' => $this->certificate_number,
            'issued' => $this->issued_at?->format('d M Y') ?? 'N/A',
            'image' => $this->imageUrl(),
            'card_type' => $this->card_type ?: self::CARD_TYPE_ONE,
            'fields' => $fields,
            'type_two' => [
                'reference_code' => $this->reference_code ?: 'RBTL/14',
                'issue_location' => $this->issue_location ?: 'NEW DELHI',
                'particulars' => $this->particulars ?: 'N/A',
                'natural_faces' => $this->natural_faces ?: 'N/A',
                'artificial_faces' => $this->artificial_faces ?: 'N/A',
                'test_carried_out' => $this->test_carried_out ?: 'N/A',
                'xray_results' => $this->xray_results ?: 'N/A',
                'conclusions' => $this->conclusions ?: 'N/A',
                'genus_type' => $this->genus_type ?: 'N/A',
                'certificate_title' => $this->certificate_title ?: 'NATURAL RUDRAKSHA',
            ],
        ];
    }

    /**
     * @return list<string>
     */
    public static function cardTypes(): array
    {
        return [self::CARD_TYPE_ONE, self::CARD_TYPE_TWO, self::CARD_TYPE_BOTH];
    }

    public function showsTypeOne(): bool
    {
        return in_array($this->card_type ?: self::CARD_TYPE_ONE, [self::CARD_TYPE_ONE, self::CARD_TYPE_BOTH], true);
    }

    public function showsTypeTwo(): bool
    {
        return in_array($this->card_type ?: self::CARD_TYPE_ONE, [self::CARD_TYPE_TWO, self::CARD_TYPE_BOTH], true);
    }

    public function imageUrl(): string
    {
        if (! $this->image_path) {
            return '/images/rbtl/service-mukhi.png';
        }

        if (str_starts_with($this->image_path, '/')) {
            return $this->image_path;
        }

        return Storage::url($this->image_path);
    }
}
