<script setup>
import QRCode from 'qrcode';
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';

// The certificate replica is drawn at a fixed design width so its layout and
// proportions stay identical everywhere. On narrow screens we scale the whole
// card down to fit the available width instead of reflowing or scrolling it.
const CARD_DESIGN_WIDTH = 600;

const fitEl = ref(null);
const cardEl = ref(null);
const scale = ref(1);
const fitHeight = ref(null);
let resizeObserver = null;

const recomputeScale = () => {
    if (!fitEl.value || !cardEl.value) {
        return;
    }

    const available = fitEl.value.clientWidth;
    const ratio = Math.min(1, available / CARD_DESIGN_WIDTH);

    scale.value = ratio;
    fitHeight.value = `${cardEl.value.offsetHeight * ratio}px`;
};

const props = defineProps({
    result: {
        type: Object,
        default: () => ({ number: '', issued: '', image: '', fields: [] }),
    },
    searched: {
        type: String,
        default: '',
    },
    verificationUrl: {
        type: String,
        default: '',
    },
    notFound: {
        type: Boolean,
        default: false,
    },
    content: {
        type: Object,
        required: true,
    },
});

const qrCode = ref('');

const detailMap = computed(() => Object.fromEntries(
    (props.result.fields || []).map((field) => [field.k, field.v]),
));

const fieldLabels = computed(() => props.content.result.field_labels || {});
const preview = computed(() => props.content.certificate_preview || {});

const fieldValue = (key, fallback = 'N/A') => detailMap.value[key] || fallback;

const detailRows = computed(() => [
    { label: fieldLabels.value.certificate, value: fieldValue('Certificate') },
    { label: fieldLabels.value.weight, value: fieldValue('Weight') },
    { label: fieldLabels.value.shape_cut, value: fieldValue('Shape/Cut') },
    { label: fieldLabels.value.dimension, value: fieldValue('Dimension') },
    { label: fieldLabels.value.colour, value: fieldValue('Colour') },
    { label: fieldLabels.value.refractive_index, value: fieldValue('Refractive Index') },
    { label: fieldLabels.value.specific_gravity, value: fieldValue('Specific Gravity') },
    { label: fieldLabels.value.origin, value: fieldValue('Origin') },
    { label: fieldLabels.value.issued_to, value: fieldValue('Issued to') },
    { label: fieldLabels.value.remarks, value: fieldValue('Remarks') },
]);

watch(
    () => props.verificationUrl,
    async (url) => {
        if (!url) {
            qrCode.value = '';
            return;
        }

        qrCode.value = await QRCode.toDataURL(url, {
            width: 180,
            margin: 1,
            color: {
                dark: '#1C1B19',
                light: '#FFFFFF',
            },
        });

        // Card height can shift once the QR renders — re-fit after it paints.
        await nextTick();
        recomputeScale();
    },
    { immediate: true },
);

onMounted(() => {
    resizeObserver = new ResizeObserver(recomputeScale);

    if (fitEl.value) {
        resizeObserver.observe(fitEl.value);
    }

    if (cardEl.value) {
        resizeObserver.observe(cardEl.value);
    }

    recomputeScale();
});

onBeforeUnmount(() => {
    resizeObserver?.disconnect();
    resizeObserver = null;
});
</script>

<template>
    <section v-if="notFound" style="max-width:720px;margin:0 auto;padding:0 32px 40px;">
        <div
            style="background:#FBFAF7;border:1px dashed rgba(28,27,25,0.2);border-radius:20px;padding:44px 36px;text-align:center;"
        >
            <div style="font-size:32px;color:#9A968D;margin-bottom:14px;">☼</div>
            <h3
                style="font-family:'Cormorant Garamond',serif;font-weight:600;font-size:24px;margin:0 0 10px;"
            >
                {{ content.result.not_found_title }}
            </h3>
            <p style="font-size:14.5px;color:#6B6862;line-height:1.6;margin:0 auto;max-width:400px;">
                {{ content.result.not_found_prefix }}
                <strong style="color:#1C1B19;">{{ searched }}</strong>.
                {{ content.result.not_found_suffix }}
            </p>
        </div>
    </section>

    <section v-else class="rbtl-cert-section" style="max-width:1200px;margin:0 auto;padding:0 32px 54px;">
        <div
            style="display:grid;grid-template-columns:0.95fr 1.05fr;gap:28px;align-items:start;"
            class="vgtl-certificate-layout"
        >
            <div style="background:#FFFFFF;border:1px solid rgba(28,27,25,0.1);border-radius:20px;overflow:hidden;">
                <div
                    style="background:#234A3E;color:#EDEAE2;padding:26px 32px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;"
                >
                    <div style="display:flex;align-items:center;gap:14px;">
                        <span
                            style="display:grid;place-items:center;width:40px;height:40px;border-radius:50%;background:rgba(237,234,226,0.14);font-size:18px;"
                        >✓</span>
                        <div>
                            <div
                                style="font-size:12px;letter-spacing:0.14em;text-transform:uppercase;color:rgba(237,234,226,0.65);"
                            >
                                {{ content.result.verified_label }}
                            </div>
                            <div
                                style="font-family:'Cormorant Garamond',serif;font-size:24px;font-weight:600;margin-top:2px;"
                            >
                                {{ result.number }}
                            </div>
                        </div>
                    </div>
                    <span style="font-size:12.5px;color:rgba(237,234,226,0.7);">{{ content.result.issued_prefix }} {{ result.issued }}</span>
                </div>

                <div style="padding:32px;">
                    <div
                        style="border:1px solid rgba(28,27,25,0.08);border-radius:16px;overflow:hidden;background:#FBFAF7;margin-bottom:28px;"
                    >
                        <img
                            :src="result.image"
                            :alt="content.result.image_alt"
                            style="width:100%;height:240px;object-fit:cover;display:block;"
                        />
                    </div>

                    <div style="display:flex;flex-direction:column;gap:14px;">
                        <div
                            v-for="field in detailRows"
                            :key="field.label"
                            style="display:grid;grid-template-columns:150px 1fr;gap:14px;border-bottom:1px solid rgba(28,27,25,0.08);padding-bottom:14px;"
                            class="vgtl-certificate-row"
                        >
                            <div
                                style="font-size:12px;letter-spacing:0.12em;text-transform:uppercase;color:#9A968D;font-weight:600;"
                            >
                                {{ field.label }}
                            </div>
                            <div style="font-size:16px;color:#1C1B19;font-weight:700;">
                                {{ field.value }}
                            </div>
                        </div>
                    </div>

                    <p style="font-size:13px;color:#6B6862;line-height:1.6;margin:26px 0 0;">
                        {{ content.result.registry_note }}
                    </p>
                </div>
            </div>

            <div ref="fitEl" class="rbtl-cert-fit" :style="{ height: fitHeight }">
            <div
                ref="cardEl"
                class="rbtl-cert-scale"
                :style="{ width: '600px', transform: `scale(${scale})`, transformOrigin: 'top left' }"
            >
            <aside
                class="rbtl-cert-card"
                style="background:#FFFFFF;border:2px solid #1C1B19;border-radius:0;padding:20px 22px 18px;box-shadow:0 24px 60px rgba(35,74,62,0.08);"
            >
                <div class="rbtl-cert-head" style="display:grid;grid-template-columns:92px 1fr 104px;gap:16px;align-items:start;">
                    <div
                        class="rbtl-cert-logo"
                        style="width:92px;height:92px;border:4px solid #D9B64C;border-radius:50%;background:#173F58;display:grid;place-items:center;color:#F6F4EF;font-family:'Cormorant Garamond',serif;font-weight:700;font-size:28px;position:relative;overflow:hidden;"
                    >
                        <span style="position:absolute;inset:12px;border:1px solid rgba(246,244,239,0.4);border-radius:50%;"></span>
                        {{ preview.logo_text }}
                    </div>

                    <div class="rbtl-cert-title" style="min-width:0;">
                        <div style="display:flex;align-items:flex-end;justify-content:center;gap:8px;flex-wrap:wrap;">
                            <span
                                class="rbtl-brand-sub"
                                style="font-size:18px;font-weight:800;font-style:italic;letter-spacing:0.04em;line-height:1.1;color:#1C1B19;"
                            >{{ preview.brand_title }}</span>
                        </div>
                        <div class="rbtl-brand-tag" style="font-size:15px;font-weight:800;text-align:center;margin-top:7px;line-height:1.25;">
                            {{ preview.brand_tagline }}
                        </div>
                        <div style="height:1px;background:#B8B8B8;margin:8px 0 4px;"></div>
                        <div
                            style="font-size:12px;font-weight:800;text-align:center;letter-spacing:0.08em;text-transform:uppercase;"
                        >
                            {{ preview.report_title }}
                        </div>
                        <div style="height:1px;background:#B8B8B8;margin-top:7px;"></div>
                    </div>

                    <div style="display:grid;place-items:center;">
                        <img
                            v-if="qrCode"
                            :src="qrCode"
                            :alt="preview.qr_alt"
                            style="width:104px;height:104px;display:block;"
                        />
                    </div>
                </div>

                <div class="rbtl-cert-body" style="display:grid;grid-template-columns:1fr 130px;gap:24px;margin-top:18px;align-items:end;">
                    <div style="display:flex;flex-direction:column;gap:5px;">
                        <div class="rbtl-cert-line" style="display:grid;grid-template-columns:145px 1fr;gap:8px;font-size:17px;line-height:1.15;">
                            <strong>{{ fieldLabels.certificate }}</strong>
                            <span style="font-weight:800;color:#D41414;">: {{ fieldValue('Certificate') }}</span>
                        </div>
                        <div class="rbtl-cert-line" style="display:grid;grid-template-columns:145px 1fr;gap:8px;font-size:17px;line-height:1.15;">
                            <strong>{{ fieldLabels.weight }}</strong>
                            <span style="font-weight:800;color:#D41414;">: {{ fieldValue('Weight') }}</span>
                        </div>
                        <div class="rbtl-cert-line" style="display:grid;grid-template-columns:145px 1fr;gap:8px;font-size:17px;line-height:1.15;">
                            <strong>{{ fieldLabels.shape_cut }}</strong>
                            <span style="font-weight:800;">: {{ fieldValue('Shape/Cut') }}</span>
                        </div>
                        <div class="rbtl-cert-line" style="display:grid;grid-template-columns:145px 1fr;gap:8px;font-size:17px;line-height:1.15;">
                            <strong>{{ fieldLabels.dimension }}</strong>
                            <span style="font-weight:800;">: {{ fieldValue('Dimension') }}</span>
                        </div>
                        <div class="rbtl-cert-line" style="display:grid;grid-template-columns:145px 1fr;gap:8px;font-size:17px;line-height:1.15;">
                            <strong>{{ fieldLabels.colour }}</strong>
                            <span style="font-weight:800;">: {{ fieldValue('Colour') }}</span>
                        </div>
                        <div class="rbtl-cert-line" style="display:grid;grid-template-columns:145px 1fr;gap:8px;font-size:17px;line-height:1.15;">
                            <strong>{{ fieldLabels.refractive_index }}</strong>
                            <span style="font-weight:800;">: {{ fieldValue('Refractive Index') }}</span>
                        </div>
                        <div class="rbtl-cert-line" style="display:grid;grid-template-columns:145px 1fr;gap:8px;font-size:17px;line-height:1.15;">
                            <strong>{{ fieldLabels.specific_gravity }}</strong>
                            <span style="font-weight:800;">: {{ fieldValue('Specific Gravity') }}</span>
                        </div>
                        <div class="rbtl-cert-line" style="display:grid;grid-template-columns:145px 1fr;gap:8px;font-size:17px;line-height:1.15;">
                            <strong>{{ fieldLabels.origin }}</strong>
                            <span style="font-weight:800;">: {{ fieldValue('Origin') }}</span>
                        </div>
                        <div class="rbtl-cert-line" style="display:grid;grid-template-columns:145px 1fr;gap:8px;font-size:17px;line-height:1.15;">
                            <strong>{{ fieldLabels.issued_to }}</strong>
                            <span style="font-weight:800;">: {{ fieldValue('Issued to') }}</span>
                        </div>
                        <div class="rbtl-cert-line" style="display:grid;grid-template-columns:145px 1fr;gap:8px;font-size:17px;line-height:1.15;">
                            <strong>{{ fieldLabels.remarks }}</strong>
                            <span style="font-weight:800;">: {{ fieldValue('Remarks') }}</span>
                        </div>
                    </div>

                    <div class="rbtl-cert-media" style="display:flex;flex-direction:column;align-items:center;gap:10px;">
                        <img
                            :src="result.image"
                            :alt="preview.item_alt"
                            style="width:100%;height:165px;object-fit:cover;object-position:center;border-radius:4px;"
                        />
                        <div style="display:flex;flex-direction:column;align-items:center;line-height:1;">
                            <span style="font-family:'Cormorant Garamond',serif;font-style:italic;font-size:30px;color:#173F58;transform:rotate(-6deg);">{{ preview.signature_name }}</span>
                            <span style="width:100%;height:1px;background:rgba(28,27,25,0.35);margin-top:2px;"></span>
                            <span style="font-size:10px;letter-spacing:0.08em;text-transform:uppercase;color:#6B6862;margin-top:4px;">{{ preview.signature_label }}</span>
                        </div>
                    </div>
                </div>
            </aside>
            </div>
            </div>
        </div>
    </section>
</template>

<style>
/* Certificate replica — keep the exact card layout everywhere and scale the
   whole card down to fit narrow screens (see recomputeScale in the script),
   so it never reflows or scrolls, matching how the physical card looks. */

/* min-width:0 lets this shrink inside the grid track; the box is sized to the
   scaled card height by JS so no empty gap is left below it. */
.rbtl-cert-fit {
    min-width: 0;
}

/* Tighten the outer padding on phones so the card can use more width. */
@media (max-width: 560px) {
    .rbtl-cert-section {
        padding-left: 16px !important;
        padding-right: 16px !important;
    }
}
</style>
