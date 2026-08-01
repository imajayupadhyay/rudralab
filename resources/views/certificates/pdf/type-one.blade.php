@php
    $labels = $content['result']['field_labels'] ?? [];
    $preview = $content['certificate_preview'] ?? [];
    $field = fn (string $key, string $fallback = 'N/A'): string => (string) ($fields[$key] ?? $fallback);
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 0;
            size: {{ $page['width'] }}px {{ $page['height'] }}px;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            height: {{ $page['height'] }}px;
            margin: 0;
            padding: 0;
            width: {{ $page['width'] }}px;
        }

        body {
            background: #ffffff;
            color: #1c1b19;
            font-family: Arial, Helvetica, sans-serif;
        }

        .certificate {
            background: #ffffff;
            border: 2px solid #1c1b19;
            height: {{ $page['height'] - 4 }}px;
            overflow: hidden;
            padding: 0;
            position: relative;
            width: {{ $page['width'] - 4 }}px;
        }

        .logo {
            height: 92px;
            left: 24px;
            overflow: visible;
            position: absolute;
            top: 22px;
            width: 122px;
        }

        .logo img {
            height: 126px;
            left: -20px;
            position: absolute;
            top: -17px;
            width: 126px;
        }

        .title {
            left: 132px;
            position: absolute;
            top: 22px;
            width: 324px;
        }

        .brand {
            font-size: 17px;
            font-style: italic;
            font-weight: 800;
            letter-spacing: 0.04em;
            line-height: 1.1;
            text-align: center;
        }

        .tagline {
            font-size: 14px;
            font-weight: 800;
            line-height: 1.25;
            margin-top: 7px;
            text-align: center;
        }

        .rule {
            background: #b8b8b8;
            height: 1px;
            margin: 8px 0 4px;
        }

        .report-title {
            font-size: 11.5px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-align: center;
            text-transform: uppercase;
        }

        .qr {
            background: #ffffff;
            height: 104px;
            position: absolute;
            right: 24px;
            top: 22px;
            width: 104px;
        }

        .rows {
            left: 24px;
            position: absolute;
            top: 144px;
            width: 398px;
        }

        .line {
            display: block;
            font-size: 16px;
            line-height: 1.16;
            margin-bottom: 4px;
            width: 100%;
        }

        .line:last-child {
            margin-bottom: 0;
        }

        .label {
            display: inline-block;
            font-weight: 800;
            vertical-align: top;
            width: 145px;
        }

        .value {
            display: inline-block;
            font-weight: 800;
            padding-left: 8px;
            vertical-align: top;
            width: 245px;
        }

        .red {
            color: #d41414;
        }

        .media {
            position: absolute;
            right: 24px;
            text-align: center;
            top: 144px;
            width: 130px;
        }

        .item-photo {
            border-radius: 4px;
            display: block;
            height: 165px;
            width: 130px;
        }

        .signature {
            display: block;
            height: 54px;
            margin: 10px auto 0;
            width: 122px;
        }

        .signature-rule {
            background: rgba(28, 27, 25, 0.35);
            display: block;
            height: 1px;
            margin-top: 2px;
            width: 122px;
        }

        .signature-label {
            color: #6b6862;
            display: block;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.08em;
            margin-top: 4px;
            text-transform: uppercase;
        }

        .website {
            bottom: 10px;
            color: #173f58;
            font-size: 11px;
            font-weight: 800;
            left: 0;
            letter-spacing: 0.1em;
            position: absolute;
            right: 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <main class="certificate">
        <div class="logo">
            @if ($assets['logo'])
                <img src="{{ $assets['logo'] }}" alt="">
            @endif
        </div>

        <section class="title">
            <div class="brand">{{ $preview['brand_title'] ?? 'RBTL Certified' }}</div>
            <div class="tagline">{{ $preview['brand_tagline'] ?? 'Analysis - Research - Authentication' }}</div>
            <div class="rule"></div>
            <div class="report-title">{{ $preview['report_title'] ?? 'Identification Report' }}</div>
            <div class="rule"></div>
        </section>

        <img class="qr" src="{{ $qrCode }}" alt="Certificate verification QR code">

        <section class="rows">
            <div class="line"><span class="label">{{ $labels['certificate'] ?? 'Certificate' }}</span><span class="value red">: {{ $field('Certificate') }}</span></div>
            <div class="line"><span class="label">{{ $labels['weight'] ?? 'Weight' }}</span><span class="value red">: {{ $field('Weight') }}</span></div>
            <div class="line"><span class="label">{{ $labels['shape_cut'] ?? 'Shape/Cut' }}</span><span class="value">: {{ $field('Shape/Cut') }}</span></div>
            <div class="line"><span class="label">{{ $labels['dimension'] ?? 'Dimension' }}</span><span class="value">: {{ $field('Dimension') }}</span></div>
            <div class="line"><span class="label">{{ $labels['colour'] ?? 'Colour' }}</span><span class="value">: {{ $field('Colour') }}</span></div>
            <div class="line"><span class="label">{{ $labels['refractive_index'] ?? 'Refractive Index' }}</span><span class="value">: {{ $field('Refractive Index') }}</span></div>
            <div class="line"><span class="label">{{ $labels['specific_gravity'] ?? 'Specific Gravity' }}</span><span class="value">: {{ $field('Specific Gravity') }}</span></div>
            <div class="line"><span class="label">{{ $labels['origin'] ?? 'Origin' }}</span><span class="value">: {{ $field('Origin') }}</span></div>
            <div class="line"><span class="label">{{ $labels['issued_to'] ?? 'Issued to' }}</span><span class="value">: {{ $field('Issued to') }}</span></div>
            <div class="line"><span class="label">{{ $labels['remarks'] ?? 'Remarks' }}</span><span class="value">: {{ $field('Remarks') }}</span></div>
        </section>

        <section class="media">
            <img class="item-photo" src="{{ $typeOneImage }}" alt="">
            @if ($assets['signature'])
                <img class="signature" src="{{ $assets['signature'] }}" alt="">
            @endif
            <span class="signature-rule"></span>
            <span class="signature-label">{{ $preview['signature_label'] ?? 'Authorised Signatory' }}</span>
        </section>

        @if ($preview['website'] ?? null)
            <div class="website">{{ $preview['website'] }}</div>
        @endif
    </main>
</body>
</html>
