<script setup>
import QRCode from 'qrcode';
import { computed, ref, watch } from 'vue';

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
});

const qrCode = ref('');

const detailRows = computed(() => props.result.fields || []);

const detailMap = computed(() => Object.fromEntries(
    detailRows.value.map((field) => [field.k, field.v]),
));

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
    },
    { immediate: true },
);
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
                No record found
            </h3>
            <p style="font-size:14.5px;color:#6B6862;line-height:1.6;margin:0 auto;max-width:400px;">
                We couldn't find a certificate matching
                <strong style="color:#1C1B19;">{{ searched }}</strong>. Double-check the number, or reach out to our team
                for assistance.
            </p>
        </div>
    </section>

    <section v-else style="max-width:1200px;margin:0 auto;padding:0 32px 54px;">
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
                                Verified &amp; Authentic
                            </div>
                            <div
                                style="font-family:'Cormorant Garamond',serif;font-size:24px;font-weight:600;margin-top:2px;"
                            >
                                {{ result.number }}
                            </div>
                        </div>
                    </div>
                    <span style="font-size:12.5px;color:rgba(237,234,226,0.7);">Issued {{ result.issued }}</span>
                </div>

                <div style="padding:32px;">
                    <div
                        style="border:1px solid rgba(28,27,25,0.08);border-radius:16px;overflow:hidden;background:#FBFAF7;margin-bottom:28px;"
                    >
                        <img
                            :src="result.image"
                            alt="Certified Rudra bead"
                            style="width:100%;height:240px;object-fit:cover;display:block;"
                        />
                    </div>

                    <div style="display:flex;flex-direction:column;gap:14px;">
                        <div
                            v-for="field in detailRows"
                            :key="field.k"
                            style="display:grid;grid-template-columns:150px 1fr;gap:14px;border-bottom:1px solid rgba(28,27,25,0.08);padding-bottom:14px;"
                            class="vgtl-certificate-row"
                        >
                            <div
                                style="font-size:12px;letter-spacing:0.12em;text-transform:uppercase;color:#9A968D;font-weight:600;"
                            >
                                {{ field.k }}
                            </div>
                            <div style="font-size:16px;color:#1C1B19;font-weight:700;">
                                {{ field.v }}
                            </div>
                        </div>
                    </div>

                    <p style="font-size:13px;color:#6B6862;line-height:1.6;margin:26px 0 0;">
                        This record is held in the RBTL Rudra Beads Testing Lab registry. Scan the QR code on the certificate
                        preview to open this exact verification record.
                    </p>
                </div>
            </div>

            <aside
                style="background:#FFFFFF;border:2px solid #1C1B19;border-radius:0;padding:20px 22px 18px;box-shadow:0 24px 60px rgba(35,74,62,0.08);"
            >
                <div style="display:grid;grid-template-columns:92px 1fr 104px;gap:16px;align-items:start;">
                    <div
                        style="height:92px;border:4px solid #D9B64C;border-radius:50%;background:#173F58;display:grid;place-items:center;color:#F6F4EF;font-family:'Cormorant Garamond',serif;font-weight:700;font-size:28px;position:relative;overflow:hidden;"
                    >
                        <span style="position:absolute;inset:12px;border:1px solid rgba(246,244,239,0.4);border-radius:50%;"></span>
                        R
                    </div>

                    <div style="min-width:0;">
                        <div style="display:flex;align-items:flex-end;gap:8px;flex-wrap:wrap;">
                            <span
                                style="font-family:'Manrope',sans-serif;font-weight:800;font-size:31px;line-height:1;color:#0B2746;border-bottom:4px solid #0B2746;"
                            >RBTL</span>
                            <span
                                style="font-size:18px;font-weight:800;font-style:italic;letter-spacing:0.04em;line-height:1.1;color:#1C1B19;"
                            >RUDRA BEADS TESTING LAB</span>
                        </div>
                        <div style="font-size:15px;font-weight:800;text-align:center;margin-top:7px;line-height:1.25;">
                            Analysis - Research - Authentication
                        </div>
                        <div style="height:1px;background:#B8B8B8;margin:8px 0 4px;"></div>
                        <div
                            style="font-size:12px;font-weight:800;text-align:center;letter-spacing:0.08em;text-transform:uppercase;"
                        >
                            Identification Report
                        </div>
                        <div style="height:1px;background:#B8B8B8;margin-top:7px;"></div>
                    </div>

                    <div style="display:grid;place-items:center;">
                        <img
                            v-if="qrCode"
                            :src="qrCode"
                            alt="Certificate verification QR code"
                            style="width:104px;height:104px;display:block;"
                        />
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 130px;gap:24px;margin-top:18px;align-items:end;">
                    <div style="display:flex;flex-direction:column;gap:5px;">
                        <div style="display:grid;grid-template-columns:145px 1fr;gap:8px;font-size:17px;line-height:1.15;">
                            <strong>Certificate</strong>
                            <span style="font-weight:800;color:#D41414;">: {{ detailMap.Certificate }}</span>
                        </div>
                        <div style="display:grid;grid-template-columns:145px 1fr;gap:8px;font-size:17px;line-height:1.15;">
                            <strong>Weight</strong>
                            <span style="font-weight:800;color:#D41414;">: {{ detailMap.Weight }}</span>
                        </div>
                        <div style="display:grid;grid-template-columns:145px 1fr;gap:8px;font-size:17px;line-height:1.15;">
                            <strong>Shape/Cut</strong>
                            <span style="font-weight:800;">: {{ detailMap['Shape/Cut'] }}</span>
                        </div>
                        <div style="display:grid;grid-template-columns:145px 1fr;gap:8px;font-size:17px;line-height:1.15;">
                            <strong>Dimension</strong>
                            <span style="font-weight:800;">: {{ detailMap.Dimension }}</span>
                        </div>
                        <div style="display:grid;grid-template-columns:145px 1fr;gap:8px;font-size:17px;line-height:1.15;">
                            <strong>Colour</strong>
                            <span style="font-weight:800;">: {{ detailMap.Colour }}</span>
                        </div>
                        <div style="display:grid;grid-template-columns:145px 1fr;gap:8px;font-size:17px;line-height:1.15;">
                            <strong>Refractive<br />Index</strong>
                            <span style="font-weight:800;">: {{ detailMap['Refractive Index'] }}</span>
                        </div>
                        <div style="display:grid;grid-template-columns:145px 1fr;gap:8px;font-size:17px;line-height:1.15;">
                            <strong>Specific<br />Gravity</strong>
                            <span style="font-weight:800;">: {{ detailMap['Specific Gravity'] }}</span>
                        </div>
                        <div style="display:grid;grid-template-columns:145px 1fr;gap:8px;font-size:17px;line-height:1.15;">
                            <strong>Remarks</strong>
                            <span style="font-weight:800;">: {{ detailMap.Remarks }}</span>
                        </div>
                    </div>

                    <div style="display:flex;flex-direction:column;align-items:center;gap:22px;">
                        <img
                            :src="result.image"
                            alt="Certified bead item"
                            style="width:100%;height:165px;object-fit:cover;object-position:center;border-radius:4px;"
                        />
                        <div style="font-family:'Cormorant Garamond',serif;font-style:italic;font-size:26px;color:#2C2A68;transform:rotate(-8deg);">
                            RBTL
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </section>
</template>
