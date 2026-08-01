@php
    $typeTwo = $payload['type_two'] ?? [];
    $field = fn (string $key, string $fallback = 'N/A'): string => (string) ($fields[$key] ?? $fallback);
    $typeTwoValue = fn (string $key, string $fallback = 'N/A'): string => (string) ($typeTwo[$key] ?? $fallback);
    $issued = strtoupper((string) ($payload['issued'] ?? 'N/A'));
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
            color: #263449;
            font-family: Arial, Helvetica, sans-serif;
        }

        .pdf-card-frame {
            height: {{ $page['height'] }}px;
            overflow: hidden;
            position: relative;
            width: {{ $page['width'] }}px;
        }

        .certificate {
            background: #f4f8fb;
            border: 3px solid #2d5db7;
            border-radius: 34px;
            height: {{ $page['design_height'] - 6 }}px;
            left: 0;
            overflow: hidden;
            padding: 0;
            position: absolute;
            top: 0;
            transform: scale({{ $page['scale'] }});
            transform-origin: top left;
            width: {{ $page['design_width'] - 6 }}px;
        }

        .white-inset {
            border: 7px solid rgba(255, 255, 255, 0.72);
            border-radius: 30px;
            bottom: 0;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            z-index: 1;
        }

        .inner-rule {
            border: 1.5px solid rgba(45, 93, 183, 0.65);
            border-radius: 27px;
            bottom: 10px;
            left: 10px;
            position: absolute;
            right: 10px;
            top: 10px;
            z-index: 2;
        }

        .logo-lockup {
            height: 112px;
            left: 31px;
            position: absolute;
            top: 24px;
            width: 260px;
            z-index: 3;
        }

        .logo-symbol {
            height: 82px;
            left: 0;
            overflow: hidden;
            position: absolute;
            top: 4px;
            width: 92px;
        }

        .logo-symbol img {
            height: 140px;
            left: 0;
            position: absolute;
            top: -18px;
            width: 140px;
        }

        .logo-word {
            color: #2b6382;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 54px;
            font-weight: 900;
            left: 92px;
            letter-spacing: 0.055em;
            line-height: 1;
            position: absolute;
            top: 22px;
        }

        .brand-rule {
            border-left: 6px solid #e3a21c;
            border-right: 5px solid #2d5db7;
            height: 112px;
            left: 307px;
            position: absolute;
            top: 24px;
            width: 23px;
            z-index: 3;
        }

        .qr {
            background: #ffffff;
            height: 108px;
            left: 346px;
            padding: 3px;
            position: absolute;
            top: 25px;
            width: 108px;
            z-index: 4;
        }

        .lab-brand {
            position: absolute;
            right: 48px;
            text-align: center;
            top: 27px;
            width: 400px;
            z-index: 3;
        }

        .rudraksha-image {
            display: block;
            height: 52px;
            margin: 0 auto 2px;
            width: 76px;
        }

        .rudraksha-word {
            color: #c83f50;
            display: block;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 50px;
            font-style: italic;
            font-weight: 700;
            letter-spacing: -0.045em;
            line-height: 0.92;
        }

        .lab-brand strong {
            color: #252d3c;
            display: block;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 21px;
            letter-spacing: 0.01em;
            margin-top: 10px;
        }

        .report-meta {
            font-family: Georgia, "Times New Roman", serif;
            left: 38px;
            line-height: 1.35;
            position: absolute;
            top: 151px;
            z-index: 4;
        }

        .report-number {
            font-size: 19px;
            font-weight: 700;
        }

        .report-number b {
            color: #c83f50;
        }

        .report-location {
            font-size: 16px;
            font-weight: 700;
            margin-top: 2px;
        }

        .report-title {
            border-bottom: 2px solid rgba(200, 63, 80, 0.58);
            color: #c83f50;
            display: inline-block;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 22px;
            font-weight: 700;
            left: 38px;
            line-height: 1.05;
            position: absolute;
            text-transform: uppercase;
            top: 211px;
            z-index: 4;
        }

        .watermark {
            left: 275px;
            opacity: 0.055;
            position: absolute;
            top: 233px;
            width: 275px;
            z-index: 1;
        }

        .details {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
            left: 40px;
            line-height: 1.08;
            position: absolute;
            top: 250px;
            width: 530px;
            z-index: 3;
        }

        .detail-row {
            height: 23px;
            position: relative;
        }

        .detail-label {
            color: #30384a;
            font-weight: 400;
            left: 0;
            position: absolute;
            top: 0;
            width: 200px;
        }

        .detail-value {
            color: #3159a3;
            font-weight: 400;
            left: 213px;
            position: absolute;
            right: 0;
            top: 0;
        }

        .strong {
            font-weight: 800;
        }

        .purple {
            color: #79428f;
        }

        .right-panel {
            bottom: 76px;
            left: 520px;
            position: absolute;
            right: 40px;
            top: 175px;
            z-index: 4;
        }

        .right-panel h2 {
            color: #c83f50;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 26px;
            font-weight: 700;
            line-height: 1.04;
            margin: 0 0 8px 50px;
            text-align: center;
            text-transform: uppercase;
            width: 300px;
        }

        .photo-frame {
            background: #ffffff;
            border: 4px solid #4f4560;
            height: 158px;
            margin-left: 80px;
            overflow: hidden;
            padding: 7px;
            width: 235px;
        }

        .photo-frame img {
            display: block;
            height: 136px;
            width: 213px;
        }

        .sign-area {
            height: 124px;
            margin: 4px 0 0 50px;
            position: relative;
            text-align: center;
            width: 350px;
        }

        .om {
            height: 100px;
            left: -75px;
            position: absolute;
            top: -100px;
            width: 100px;
        }

        .signature {
            height: 76px;
            position: absolute;
            right: 7px;
            top: -3px;
            width: 220px;
        }

        .sign-divider {
            background: #2d5db7;
            height: 2px;
            left: 4px;
            position: absolute;
            right: 4px;
            top: 69px;
        }

        .sign-copy {
            color: #3c4a88;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 12.5px;
            font-weight: 700;
            left: 0;
            letter-spacing: 0.025em;
            line-height: 1.16;
            position: absolute;
            right: 0;
            top: 75px;
        }

        .sign-copy strong {
            color: #28355e;
            display: block;
            font-size: 15px;
        }

        .website {
            color: #79428f;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.03em;
            position: absolute;
            right: -48px;
            text-align: center;
            top: 315px;
            transform: rotate(90deg);
            transform-origin: center;
            width: 176px;
            z-index: 5;
        }

        .origin {
            background: #3157b5;
            border-radius: 20px 20px 3px 20px;
            bottom: 18px;
            color: #ffffff;
            font-size: 31px;
            font-weight: 900;
            left: 34px;
            letter-spacing: -0.025em;
            padding: 10px 14px 8px;
            position: absolute;
            white-space: nowrap;
            width: 320px;
            z-index: 5;
        }

        .origin b {
            color: #ffe13d;
        }

        .marks {
            bottom: 22px;
            height: 52px;
            left: 414px;
            position: absolute;
            width: 176px;
            z-index: 5;
        }

        .mark {
            height: 52px;
            overflow: hidden;
            position: absolute;
            top: 0;
            width: 52px;
        }

        .mark img {
            display: block;
            height: 52px;
            object-fit: contain;
            position: absolute;
            width: 52px;
        }

        .egac {
            left: 0;
        }

        .egac img {
            left: 0;
            top: 0;
        }

        .iaf {
            left: 62px;
        }

        .iaf img {
            left: 0;
            top: 0;
        }

        .iso {
            left: 124px;
        }

        .iso img {
            left: 0;
            top: 0;
        }

        .footer-note {
            bottom: 20px;
            color: #67758c;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 10px;
            line-height: 1.25;
            position: absolute;
            right: 34px;
            text-align: right;
            width: 300px;
            z-index: 5;
        }

        .footer-note strong {
            color: #354b74;
            display: block;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="pdf-card-frame">
    <main class="certificate">
        <div class="white-inset"></div>
        <div class="inner-rule"></div>

        <section class="logo-lockup" aria-label="RBTL">
            <span class="logo-symbol">
                @if ($assets['logo'])
                    <img src="{{ $assets['logo'] }}" alt="">
                @endif
            </span>
            <span class="logo-word">RBTL</span>
        </section>

        <div class="brand-rule"></div>
        <img class="qr" src="{{ $qrCode }}" alt="Certificate verification QR code">

        <section class="lab-brand">
            @if ($assets['rudra'])
                <img class="rudraksha-image" src="{{ $assets['rudra'] }}" alt="">
            @endif
            <span class="rudraksha-word">Rudraksha</span>
            <strong>Rudra Beads &amp; Gems Testing Lab</strong>
        </section>

        <section class="report-meta">
            <div class="report-number">RBTL REPORT NO.- <b>{{ $payload['number'] }}</b></div>
            <div class="report-location">
                ({{ $typeTwoValue('reference_code', 'RBTL/14') }}),
                {{ $typeTwoValue('issue_location', 'NEW DELHI') }},
                {{ $issued }}
            </div>
        </section>

        <div class="report-title">Rudraksha Identification Report</div>

        @if ($assets['deityWatermark'])
            <img class="watermark" src="{{ $assets['deityWatermark'] }}" alt="">
        @endif

        <section class="details">
            <div class="detail-row"><span class="detail-label">Particulars :</span><span class="detail-value">{{ $typeTwoValue('particulars') }}</span></div>
            <div class="detail-row"><span class="detail-label">Color :</span><span class="detail-value">{{ $field('Colour') }}</span></div>
            <div class="detail-row"><span class="detail-label">Weight :</span><span class="detail-value">{{ $field('Weight') }}</span></div>
            <div class="detail-row"><span class="detail-label">Dimensions <small>(mm)</small> :</span><span class="detail-value">{{ $field('Dimension') }}</span></div>
            <div class="detail-row"><span class="detail-label">Shape / Type :</span><span class="detail-value">{{ $field('Shape/Cut') }}</span></div>
            <div class="detail-row"><span class="detail-label strong">Natural Faces:</span><span class="detail-value strong purple">{{ $typeTwoValue('natural_faces') }}</span></div>
            <div class="detail-row"><span class="detail-label">Artificial Faces:</span><span class="detail-value">{{ $typeTwoValue('artificial_faces') }}</span></div>
            <div class="detail-row"><span class="detail-label">Test Carried Out:</span><span class="detail-value">{{ $typeTwoValue('test_carried_out') }}</span></div>
            <div class="detail-row"><span class="detail-label">X-Ray Results:</span><span class="detail-value">{{ $typeTwoValue('xray_results') }}</span></div>
            <div class="detail-row"><span class="detail-label">Conclusions:</span><span class="detail-value">{{ $typeTwoValue('conclusions') }}</span></div>
            <div class="detail-row"><span class="detail-label strong purple">GENUS / TYPE :</span><span class="detail-value strong purple">{{ $typeTwoValue('genus_type') }}</span></div>
        </section>

        <section class="right-panel">
            <h2>{{ $typeTwoValue('certificate_title', 'NATURAL RUDRAKSHA') }}</h2>
            <div class="photo-frame">
                <img src="{{ $typeTwoImage }}" alt="">
            </div>
            <div class="sign-area">
                @if ($assets['om'])
                    <img class="om" src="{{ $assets['om'] }}" alt="">
                @endif
                @if ($assets['signature'])
                    <img class="signature" src="{{ $assets['signature'] }}" alt="">
                @endif
                <span class="sign-divider"></span>
                <div class="sign-copy">
                    <strong>(CHIEF GEMMOLOGIST)</strong>
                    AUTHORISED RBTL SIGNATORY<br>
                    Rudraksha &amp; Gem Identification Laboratory
                </div>
            </div>
        </section>

        <div class="website">WWW.RBTL.ONLINE</div>

        <div class="origin">ORIGIN : <b>{{ $field('Origin') }}</b></div>

        <section class="marks" aria-label="Accreditation marks">
            @if ($assets['egac'])
                <span class="mark egac"><img src="{{ $assets['egac'] }}" alt=""></span>
            @endif
            @if ($assets['iaf'])
                <span class="mark iaf"><img src="{{ $assets['iaf'] }}" alt=""></span>
            @endif
            @if ($assets['iso'])
                <span class="mark iso"><img src="{{ $assets['iso'] }}" alt=""></span>
            @endif
        </section>

        <footer class="footer-note">
            <strong>RBTL - RUDRA BEADS TESTING LAB</strong>
            Analysis | Research | Authentication<br>
            Digitally verifiable certificate record
        </footer>
    </main>
    </div>
</body>
</html>
