<script setup>
defineProps({
    content: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <section
        style="max-width:1200px;margin:0 auto;padding:96px 32px 80px;display:grid;grid-template-columns:1.15fr 0.85fr;gap:64px;align-items:center;"
        class="vgtl-hero"
    >
        <div>
            <div
                style="display:inline-flex;align-items:center;gap:10px;padding:6px 14px;border:1px solid rgba(35,74,62,0.3);border-radius:100px;margin-bottom:28px;"
            >
                <span style="width:6px;height:6px;border-radius:50%;background:#234A3E;"></span>
                <span
                    style="font-size:11px;letter-spacing:0.18em;text-transform:uppercase;color:#234A3E;font-weight:600;"
                >{{ content.badge }}</span>
            </div>

            <h1
                style="font-family:'Cormorant Garamond',serif;font-weight:500;font-size:clamp(44px,6vw,76px);line-height:1.02;letter-spacing:-0.01em;margin:0 0 26px;"
            >
                {{ content.title }}<br /><em style="font-style:italic;color:#234A3E;">{{ content.highlight }}</em>
            </h1>

            <p style="font-size:16.5px;line-height:1.7;color:#4A4842;max-width:520px;margin:0 0 36px;">
                {{ content.description }}
            </p>

            <div style="display:flex;align-items:center;gap:22px;flex-wrap:wrap;">
                <a
                    :href="content.primary_button_url"
                    style="text-decoration:none;background:#234A3E;color:#F6F4EF;font-size:14px;font-weight:600;padding:15px 30px;border-radius:100px;"
                >{{ content.primary_button_text }}</a>
                <a
                    :href="content.secondary_button_url"
                    style="text-decoration:none;color:#1C1B19;font-size:14px;font-weight:600;display:inline-flex;align-items:center;gap:9px;border-bottom:1px solid rgba(28,27,25,0.25);padding-bottom:4px;"
                >{{ content.secondary_button_text }} <span style="font-family:serif;">→</span></a>
            </div>
        </div>

        <!-- Accreditations are the hero visual: shown large as the trust proof -->
        <div style="display:flex;flex-direction:column;gap:18px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <span
                    style="font-size:11px;letter-spacing:0.2em;text-transform:uppercase;font-weight:700;color:#234A3E;"
                >{{ content.accreditation_label }}</span>
                <span style="flex:1;height:1px;background:rgba(28,27,25,0.12);"></span>
            </div>

            <div style="display:flex;gap:18px;align-items:stretch;flex-wrap:wrap;">
                <div
                    style="flex:1;min-width:190px;display:flex;align-items:center;justify-content:center;background:#FFFFFF;border:1px solid rgba(28,27,25,0.08);border-radius:22px;padding:22px;box-shadow:0 26px 60px rgba(35,74,62,0.12);"
                >
                    <img
                        :src="content.egac_image"
                        :alt="content.egac_alt"
                        style="width:100%;height:auto;display:block;border-radius:10px;"
                    />
                </div>

                <div
                    style="flex:1;min-width:190px;display:flex;align-items:center;justify-content:center;background:#FFFFFF;border:1px solid rgba(28,27,25,0.08);border-radius:22px;padding:22px;box-shadow:0 26px 60px rgba(35,74,62,0.12);"
                >
                    <img
                        :src="content.iso_image"
                        :alt="content.iso_alt"
                        style="width:100%;height:auto;display:block;border-radius:10px;"
                    />
                </div>
            </div>
        </div>
    </section>
</template>
