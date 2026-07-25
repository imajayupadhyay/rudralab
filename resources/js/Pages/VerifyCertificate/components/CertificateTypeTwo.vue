<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

const DESIGN_WIDTH = 950;
const DESIGN_HEIGHT = 620;
const MAX_DISPLAY_WIDTH = 600;

const props = defineProps({
    result: {
        type: Object,
        required: true,
    },
    qrCode: {
        type: String,
        default: '',
    },
});

const fitEl = ref(null);
const scale = ref(MAX_DISPLAY_WIDTH / DESIGN_WIDTH);
const fitHeight = ref(`${DESIGN_HEIGHT * scale.value}px`);
let resizeObserver = null;

const recomputeScale = () => {
    if (!fitEl.value) {
        return;
    }

    const available = Math.min(MAX_DISPLAY_WIDTH, fitEl.value.clientWidth);
    const ratio = available / DESIGN_WIDTH;

    scale.value = ratio;
    fitHeight.value = `${DESIGN_HEIGHT * ratio}px`;
};

const detailMap = computed(() => Object.fromEntries(
    (props.result.fields || []).map((field) => [field.k, field.v]),
));

const details = computed(() => props.result.type_two || {});
const value = (key, fallback = 'N/A') => detailMap.value[key] || fallback;
const typeTwoValue = (key, fallback = 'N/A') => details.value[key] || fallback;
const issued = computed(() => (props.result.issued || 'N/A').toUpperCase());

onMounted(() => {
    resizeObserver = new ResizeObserver(recomputeScale);
    resizeObserver.observe(fitEl.value);
    recomputeScale();
});

onBeforeUnmount(() => {
    resizeObserver?.disconnect();
    resizeObserver = null;
});
</script>

<template>
    <div ref="fitEl" class="rbtl-type-two-fit" :style="{ height: fitHeight }">
        <article
            class="rbtl-type-two-card"
            :style="{ transform: `scale(${scale})` }"
            aria-label="RBTL Rudraksha identification certificate"
        >
            <header class="type-two-header">
                <div class="type-two-logo-lockup" aria-label="RBTL">
                    <span class="type-two-logo-symbol">
                        <img src="/images/rbtl/certificate-type-2/rbtl-logo.png" alt="" />
                    </span>
                    <span class="type-two-logo-word">RBTL</span>
                </div>
                <div class="type-two-brand-rule" aria-hidden="true"></div>
                <img
                    v-if="qrCode"
                    class="type-two-qr"
                    :src="qrCode"
                    :alt="`Scan to verify certificate ${result.number}`"
                />
                <div class="type-two-lab-brand">
                    <div class="type-two-rudraksha-mark">Rudraksha</div>
                    <strong>Rudra Beads &amp; Gems Testing Lab</strong>
                </div>
            </header>

            <div class="type-two-report-meta">
                <div class="type-two-report-number">RBTL REPORT NO.- <b>{{ result.number }}</b></div>
                <div class="type-two-report-location">
                    ({{ typeTwoValue('reference_code', 'RBTL/14') }}),
                    {{ typeTwoValue('issue_location', 'NEW DELHI') }},
                    {{ issued }}
                </div>
            </div>

            <div class="type-two-report-title">Rudraksha Identification Report</div>

            <img
                class="type-two-watermark"
                src="/images/rbtl/certificate-type-2/deity-watermark.png"
                alt=""
                aria-hidden="true"
            />

            <dl class="type-two-details">
                <dt>Particulars :</dt><dd>{{ typeTwoValue('particulars') }}</dd>
                <dt>Color :</dt><dd>{{ value('Colour') }}</dd>
                <dt>Weight :</dt><dd>{{ value('Weight') }}</dd>
                <dt>Dimensions <small>(mm)</small> :</dt><dd>{{ value('Dimension') }}</dd>
                <dt>Shape / Type :</dt><dd>{{ value('Shape/Cut') }}</dd>
                <dt class="strong-label">Natural Faces:</dt><dd class="strong-value">{{ typeTwoValue('natural_faces') }}</dd>
                <dt>Artificial Faces:</dt><dd>{{ typeTwoValue('artificial_faces') }}</dd>
                <dt>Test Carried Out:</dt><dd>{{ typeTwoValue('test_carried_out') }}</dd>
                <dt>X-Ray Results:</dt><dd>{{ typeTwoValue('xray_results') }}</dd>
                <dt>Conclusions:</dt><dd>{{ typeTwoValue('conclusions') }}</dd>
                <dt class="strong-label genus-label">GENUS / TYPE :</dt><dd class="strong-value">{{ typeTwoValue('genus_type') }}</dd>
            </dl>

            <section class="type-two-right-panel">
                <h2>{{ typeTwoValue('certificate_title', 'NATURAL RUDRAKSHA') }}</h2>
                <div class="type-two-photo-frame">
                    <img :src="result.image" alt="Certified Rudraksha bead" />
                </div>
                <div class="type-two-sign-area">
                    <span class="type-two-om">ॐ</span>
                    <img
                        class="type-two-signature"
                        src="/images/rbtl/certificate-type-2/signature.png"
                        alt="Authorised signature"
                    />
                    <span class="type-two-sign-divider"></span>
                    <div class="type-two-sign-copy">
                        <strong>(CHIEF GEMMOLOGIST)</strong>
                        AUTHORISED RBTL SIGNATORY<br />
                        Rudraksha &amp; Gem Identification Laboratory
                    </div>
                </div>
            </section>

            <div class="type-two-website">WWW.RBTL.ONLINE</div>

            <footer class="type-two-footer-strip">
                <div class="type-two-origin">ORIGIN : <b>{{ value('Origin') }}</b></div>
                <div class="type-two-accreditations" aria-label="Accreditation marks">
                    <span class="type-two-accreditation-mark egac">
                        <img src="/images/rbtl/certificate-type-2/egac-mark.jpeg" alt="EGAC accredited" />
                    </span>
                    <span class="type-two-accreditation-mark iaf">
                        <img src="/images/rbtl/certificate-type-2/iaf-mark.png" alt="International Accreditation Forum" />
                    </span>
                    <span class="type-two-accreditation-mark iso">
                        <img src="/images/rbtl/iso-certified.jpeg" alt="ISO 9001:2015 certified company" />
                    </span>
                </div>
                <div class="type-two-footer-note">
                    <strong>RBTL — RUDRA BEADS TESTING LAB</strong>
                    Analysis · Research · Authentication<br />
                    Digitally verifiable certificate record
                </div>
            </footer>
        </article>
    </div>
</template>

<style scoped>
.rbtl-type-two-fit {
    max-width: 600px;
    min-width: 0;
    overflow: hidden;
    width: 100%;
}

.rbtl-type-two-card {
    --blue: #245a86;
    --bright-blue: #2d5db7;
    --red: #c83f50;
    --purple: #79428f;
    --gold: #e3a21c;
    --ink: #263449;
    background: linear-gradient(100deg, rgba(255, 255, 255, 0.92), rgba(236, 246, 251, 0.95)), #f4f8fb;
    border: 3px solid var(--bright-blue);
    border-radius: 34px;
    box-shadow: 0 22px 48px rgba(25, 49, 65, 0.22), inset 0 0 0 7px rgba(255, 255, 255, 0.72);
    color: var(--ink);
    font-family: Arial, Helvetica, sans-serif;
    height: 620px;
    overflow: hidden;
    padding: 24px 31px 20px;
    position: relative;
    transform-origin: top left;
    width: 950px;
}

.rbtl-type-two-card::after {
    border: 1.5px solid rgba(45, 93, 183, 0.65);
    border-radius: 27px;
    content: '';
    inset: 10px;
    pointer-events: none;
    position: absolute;
}

.type-two-header {
    align-items: flex-start;
    display: grid;
    grid-template-columns: 270px 1fr 430px;
    height: 142px;
    position: relative;
    z-index: 3;
}

.type-two-logo-lockup {
    height: 112px;
    position: relative;
    width: 260px;
}

.type-two-logo-symbol {
    height: 82px;
    left: 0;
    overflow: hidden;
    position: absolute;
    top: 4px;
    width: 92px;
}

.type-two-logo-symbol img {
    height: 140px;
    left: -24px;
    object-fit: contain;
    position: absolute;
    top: -18px;
    width: 140px;
}

.type-two-logo-word {
    color: #2b6382;
    font-family: 'Arial Black', Arial, Helvetica, sans-serif;
    font-size: 54px;
    font-weight: 900;
    left: 92px;
    letter-spacing: 0.055em;
    line-height: 1;
    position: absolute;
    top: 22px;
}

.type-two-brand-rule {
    align-self: stretch;
    border-left: 6px solid var(--gold);
    border-right: 5px solid var(--bright-blue);
    height: 112px;
    margin-left: 8px;
    opacity: 0.85;
    width: 23px;
}

.type-two-qr {
    background: #fff;
    height: 108px;
    left: 315px;
    object-fit: contain;
    padding: 3px;
    position: absolute;
    top: 1px;
    width: 108px;
}

.type-two-lab-brand {
    padding: 3px 17px 0 0;
    text-align: center;
}

.type-two-rudraksha-mark {
    color: var(--red);
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 50px;
    font-style: italic;
    font-weight: 700;
    letter-spacing: -0.045em;
    line-height: 0.92;
    text-shadow: 0 2px 2px rgba(93, 27, 35, 0.14);
}

.type-two-rudraksha-mark::before {
    background: radial-gradient(circle at 34% 30%, #b66f40 0 6%, #6d2f1c 34%, #3f1d14 68%, #8f4f2f 100%);
    border: 2px solid rgba(102, 47, 30, 0.42);
    border-radius: 48% 52% 46% 54%;
    box-shadow: inset 5px 2px 0 rgba(255, 255, 255, 0.12), 0 2px 4px rgba(52, 25, 17, 0.22);
    content: '';
    display: block;
    height: 39px;
    margin: 0 auto 2px;
    transform: rotate(-4deg);
    width: 76px;
}

.type-two-lab-brand strong {
    color: #252d3c;
    display: block;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 21px;
    letter-spacing: 0.01em;
    margin-top: 10px;
}

.type-two-report-meta {
    font-family: Georgia, 'Times New Roman', serif;
    left: 38px;
    line-height: 1.35;
    position: absolute;
    top: 151px;
    z-index: 4;
}

.type-two-report-number {
    font-size: 19px;
    font-weight: 700;
}

.type-two-report-number b { color: var(--red); }

.type-two-report-location {
    font-size: 16px;
    font-weight: 700;
    margin-top: 2px;
}

.type-two-report-title {
    border-bottom: 2px solid rgba(200, 63, 80, 0.58);
    color: var(--red);
    display: inline-block;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 22px;
    font-weight: 700;
    left: 38px;
    line-height: 1.05;
    position: absolute;
    text-transform: uppercase;
    top: 211px;
    z-index: 4;
}

.type-two-watermark {
    left: 275px;
    opacity: 0.055;
    position: absolute;
    top: 233px;
    width: 275px;
    z-index: 1;
}

.type-two-details {
    display: grid;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 18px;
    gap: 4px 13px;
    grid-template-columns: 200px 1fr;
    left: 40px;
    line-height: 1.08;
    margin: 0;
    position: absolute;
    top: 250px;
    width: 530px;
    z-index: 3;
}

.type-two-details dt {
    color: #30384a;
    font-weight: 500;
    margin: 0;
}

.type-two-details dd {
    color: #3159a3;
    font-weight: 500;
    margin: 0;
    min-width: 0;
}

.type-two-details .strong-label,
.type-two-details .strong-value { font-weight: 800; }
.type-two-details .strong-value,
.type-two-details .genus-label { color: var(--purple); }

.type-two-right-panel {
    bottom: 76px;
    left: 520px;
    position: absolute;
    right: 40px;
    top: 175px;
    z-index: 4;
}

.type-two-right-panel h2 {
    color: var(--red);
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 26px;
    font-weight: 700;
    line-height: 1.04;
    margin: 0 0 8px 50px;
    text-align: center;
    text-transform: uppercase;
    width: 300px;
}

.type-two-photo-frame {
    background: white;
    border: 4px solid #4f4560;
    height: 158px;
    margin: 0 0 0 80px;
    overflow: hidden;
    padding: 7px;
    width: 235px;
}

.type-two-photo-frame img {
    display: block;
    filter: saturate(0.82) contrast(1.08);
    height: 100%;
    object-fit: cover;
    width: 100%;
}

.type-two-sign-area {
    height: 124px;
    margin: 4px 0 0 50px;
    position: relative;
    text-align: center;
    width: 350px;
}

.type-two-om {
    color: var(--gold);
    font-family: Georgia, serif;
    font-size: 100px;
    font-weight: 700;
    left: -75px;
    line-height: 1;
    position: absolute;
    top: -100px;
}

.type-two-signature {
    height: 76px;
    object-fit: contain;
    position: absolute;
    right: 7px;
    top: -3px;
    width: 220px;
}

.type-two-sign-divider {
    background: linear-gradient(90deg, var(--gold), var(--blue));
    height: 2px;
    left: 4px;
    position: absolute;
    right: 4px;
    top: 69px;
}

.type-two-sign-copy {
    color: #3c4a88;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 12.5px;
    font-weight: 700;
    left: 0;
    letter-spacing: 0.025em;
    line-height: 1.16;
    position: absolute;
    right: 0;
    top: 75px;
}

.type-two-sign-copy strong {
    color: #28355e;
    display: block;
    font-size: 15px;
}

.type-two-website {
    color: var(--purple);
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 18px;
    font-weight: 700;
    letter-spacing: 0.03em;
    position: absolute;
    right: 16px;
    top: 244px;
    writing-mode: vertical-rl;
    z-index: 5;
}

.type-two-footer-strip {
    align-items: end;
    bottom: 18px;
    display: grid;
    gap: 16px;
    grid-template-columns: 320px 1fr 320px;
    left: 34px;
    position: absolute;
    right: 34px;
    z-index: 5;
}

.type-two-origin {
    background: linear-gradient(90deg, #3157b5, #24488f);
    border-radius: 20px 20px 3px 20px;
    color: white;
    font-size: 31px;
    font-weight: 900;
    letter-spacing: -0.025em;
    padding: 10px 14px 8px;
    white-space: nowrap;
}

.type-two-origin b { color: #ffe13d; }

.type-two-accreditations {
    align-items: center;
    display: flex;
    gap: 12px;
    justify-content: center;
}

.type-two-accreditation-mark {
    flex: 0 0 52px;
    height: 52px;
    overflow: hidden;
    position: relative;
    width: 52px;
}

.type-two-accreditation-mark img {
    display: block;
    mix-blend-mode: multiply;
    object-fit: contain;
    position: absolute;
}

.type-two-accreditation-mark.egac img {
    height: 52px;
    inset: 0;
    width: 52px;
}

.type-two-accreditation-mark.iaf img {
    height: 74px;
    left: -8px;
    top: -14px;
    width: 74px;
}

.type-two-accreditation-mark.iso img {
    height: 54px;
    left: -18px;
    top: 0;
    width: 88px;
}

.type-two-footer-note {
    color: #67758c;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 10px;
    line-height: 1.25;
    text-align: right;
}

.type-two-footer-note strong {
    color: #354b74;
    display: block;
    font-size: 13px;
}
</style>
