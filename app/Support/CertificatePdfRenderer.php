<?php

namespace App\Support;

use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class CertificatePdfRenderer
{
    private const TYPE_ONE_WIDTH = 600;

    private const TYPE_ONE_HEIGHT = 430;

    private const TYPE_TWO_WIDTH = 950;

    private const TYPE_TWO_HEIGHT = 620;

    private const TYPE_TWO_EXPORT_WIDTH = 600;

    public function render(Certificate $certificate, string $cardType): string
    {
        if (! $certificate->supportsCardType($cardType)) {
            throw new InvalidArgumentException('This certificate does not support the requested card type.');
        }

        [$width, $height] = $this->paperSizeFor($cardType);

        $pdf = Pdf::loadView($this->viewFor($cardType), $this->viewData($certificate, $cardType));
        $pdf->setPaper([0, 0, $this->pxToPoints($width), $this->pxToPoints($height)]);
        $pdf->setOption('dpi', 96);
        $pdf->setOption('defaultFont', 'Helvetica');
        $pdf->setOption('isRemoteEnabled', false);
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isPhpEnabled', false);
        $pdf->setOption('chroot', base_path());

        return $pdf->output();
    }

    public function filename(Certificate $certificate, string $cardType): string
    {
        return sprintf(
            '%s-%s.pdf',
            $this->safeName($certificate->certificate_number),
            $this->typeSlug($cardType),
        );
    }

    public function typeSlug(string $cardType): string
    {
        return match ($cardType) {
            Certificate::CARD_TYPE_ONE => 'certificate-1',
            Certificate::CARD_TYPE_TWO => 'certificate-2',
            default => throw new InvalidArgumentException('Unsupported certificate card type.'),
        };
    }

    /**
     * @return array{0:float|int,1:float|int}
     */
    private function paperSizeFor(string $cardType): array
    {
        return match ($cardType) {
            Certificate::CARD_TYPE_ONE => [self::TYPE_ONE_WIDTH, self::TYPE_ONE_HEIGHT],
            Certificate::CARD_TYPE_TWO => [self::TYPE_TWO_EXPORT_WIDTH, self::TYPE_TWO_HEIGHT * $this->typeTwoScale()],
            default => throw new InvalidArgumentException('Unsupported certificate card type.'),
        };
    }

    private function viewFor(string $cardType): string
    {
        return match ($cardType) {
            Certificate::CARD_TYPE_ONE => 'certificates.pdf.type-one',
            Certificate::CARD_TYPE_TWO => 'certificates.pdf.type-two',
            default => throw new InvalidArgumentException('Unsupported certificate card type.'),
        };
    }

    /**
     * @return array<string, mixed>
     */
    private function viewData(Certificate $certificate, string $cardType): array
    {
        $payload = $certificate->verificationPayload();
        $verificationUrl = url('/verify-certificate').'?'.http_build_query([
            'certificate' => $certificate->certificate_number,
        ]);

        [$paperWidth, $paperHeight] = $this->paperSizeFor($cardType);
        [$designWidth, $designHeight] = $this->designSizeFor($cardType);

        return [
            'certificate' => $certificate,
            'payload' => $payload,
            'fields' => collect($payload['fields'])->mapWithKeys(fn (array $field): array => [$field['k'] => $field['v']])->all(),
            'content' => VerifyCertificatePageContent::get(),
            'verificationUrl' => $verificationUrl,
            'qrCode' => $this->qrCodeDataUri($verificationUrl, $cardType),
            'image' => $this->imageDataUri($certificate),
            'typeOneImage' => $this->croppedCertificateImageDataUri($certificate, 130, 165),
            'typeTwoImage' => $this->croppedCertificateImageDataUri($certificate, 235, 158),
            'assets' => [
                'logo' => $this->assetDataUri('images/rbtl/certificate-type-2/rbtl-logo.png'),
                'signature' => $this->assetDataUri('images/rbtl/certificate-type-2/signature.png'),
                'rudra' => $this->assetDataUri('images/rbtl/certificate-type-2/rudra.png'),
                'om' => $this->assetDataUri('images/rbtl/certificate-type-2/om.png'),
                'deityWatermark' => $this->assetDataUri('images/rbtl/certificate-type-2/deity-watermark.png'),
                'egac' => $this->assetDataUri('images/rbtl/certificate-type-2/egac-mark.jpeg'),
                'iaf' => $this->assetDataUri('images/rbtl/certificate-type-2/iaf-mark.png'),
                'iso' => $this->assetDataUri('images/rbtl/certificate-type-2/iso-mark.jpeg'),
            ],
            'page' => [
                'width' => $paperWidth,
                'height' => $paperHeight,
                'design_width' => $designWidth,
                'design_height' => $designHeight,
                'scale' => $cardType === Certificate::CARD_TYPE_TWO ? $this->typeTwoScale() : 1,
            ],
        ];
    }

    /**
     * @return array{0:int,1:int}
     */
    private function designSizeFor(string $cardType): array
    {
        return match ($cardType) {
            Certificate::CARD_TYPE_ONE => [self::TYPE_ONE_WIDTH, self::TYPE_ONE_HEIGHT],
            Certificate::CARD_TYPE_TWO => [self::TYPE_TWO_WIDTH, self::TYPE_TWO_HEIGHT],
            default => throw new InvalidArgumentException('Unsupported certificate card type.'),
        };
    }

    private function typeTwoScale(): float
    {
        return self::TYPE_TWO_EXPORT_WIDTH / self::TYPE_TWO_WIDTH;
    }

    private function qrCodeDataUri(string $url, string $cardType): string
    {
        $size = $cardType === Certificate::CARD_TYPE_TWO ? 150 : 180;

        $qrCode = new QrCode(
            data: $url,
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            size: $size,
            margin: 1,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(28, 27, 25),
            backgroundColor: new Color(255, 255, 255),
        );

        $result = (new PngWriter())->write($qrCode);

        return 'data:'.$result->getMimeType().';base64,'.base64_encode($result->getString());
    }

    private function imageDataUri(Certificate $certificate): string
    {
        $path = $this->localCertificateImagePath($certificate) ?: public_path('images/rbtl/service-mukhi.png');

        return $this->dataUriFromPath($path);
    }

    private function croppedCertificateImageDataUri(Certificate $certificate, int $width, int $height): string
    {
        $path = $this->localCertificateImagePath($certificate) ?: public_path('images/rbtl/service-mukhi.png');

        return $this->croppedDataUriFromPath($path, $width, $height) ?: $this->dataUriFromPath($path);
    }

    private function assetDataUri(string $path): string
    {
        return $this->dataUriFromPath(public_path($path));
    }

    private function dataUriFromPath(string $path): string
    {
        if (! is_file($path)) {
            return '';
        }

        $mime = mime_content_type($path) ?: 'application/octet-stream';

        return 'data:'.$mime.';base64,'.base64_encode((string) file_get_contents($path));
    }

    private function croppedDataUriFromPath(string $path, int $width, int $height): ?string
    {
        if (! is_file($path) || ! function_exists('imagecreatetruecolor')) {
            return null;
        }

        $imageInfo = getimagesize($path);

        if (! is_array($imageInfo)) {
            return null;
        }

        [$sourceWidth, $sourceHeight] = $imageInfo;
        $mime = $imageInfo['mime'] ?? '';
        $source = match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($path),
            'image/png' => imagecreatefrompng($path),
            'image/gif' => imagecreatefromgif($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? imagecreatefromwebp($path) : false,
            default => false,
        };

        if (! $source) {
            return null;
        }

        $targetRatio = $width / $height;
        $sourceRatio = $sourceWidth / $sourceHeight;

        if ($sourceRatio > $targetRatio) {
            $cropHeight = $sourceHeight;
            $cropWidth = (int) round($sourceHeight * $targetRatio);
        } else {
            $cropWidth = $sourceWidth;
            $cropHeight = (int) round($sourceWidth / $targetRatio);
        }

        $sourceX = (int) max(0, floor(($sourceWidth - $cropWidth) / 2));
        $sourceY = (int) max(0, floor(($sourceHeight - $cropHeight) / 2));
        $target = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($target, 255, 255, 255);

        imagefilledrectangle($target, 0, 0, $width, $height, $white);
        imagecopyresampled($target, $source, 0, 0, $sourceX, $sourceY, $width, $height, $cropWidth, $cropHeight);

        ob_start();
        imagejpeg($target, quality: 92);
        $contents = ob_get_clean();

        imagedestroy($source);
        imagedestroy($target);

        return is_string($contents) ? 'data:image/jpeg;base64,'.base64_encode($contents) : null;
    }

    private function localCertificateImagePath(Certificate $certificate): ?string
    {
        if (! $certificate->image_path) {
            return null;
        }

        if (str_starts_with($certificate->image_path, '/')) {
            $path = public_path(ltrim($certificate->image_path, '/'));

            return is_file($path) ? $path : null;
        }

        $path = Storage::disk('public')->path($certificate->image_path);

        return is_file($path) ? $path : null;
    }

    private function pxToPoints(float|int $px): float
    {
        return $px * 72 / 96;
    }

    private function safeName(string $value): string
    {
        $safe = preg_replace('/[^A-Za-z0-9._-]+/', '-', trim($value)) ?: 'certificate';

        return trim($safe, '-_.') ?: 'certificate';
    }
}
