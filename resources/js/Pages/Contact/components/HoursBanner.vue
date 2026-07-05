<script setup>
defineProps({
    content: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <section style="max-width:1200px;margin:0 auto;padding:20px 32px 90px;">
        <div
            style="background:#234A3E;color:#EDEAE2;border-radius:18px;padding:34px 40px;display:flex;align-items:center;justify-content:space-between;gap:24px;flex-wrap:wrap;"
        >
            <div style="display:flex;align-items:center;gap:16px;">
                <span style="font-size:14px;font-weight:700;">{{ content.icon }}</span>
                <div>
                    <div
                        style="font-size:11px;letter-spacing:0.16em;text-transform:uppercase;color:rgba(237,234,226,0.6);margin-bottom:6px;"
                    >
                        {{ content.label }}
                    </div>
                    <div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:500;">
                        {{ content.text }}
                    </div>
                </div>
            </div>
            <a
                :href="content.button_url"
                style="text-decoration:none;background:#F6F4EF;color:#234A3E;font-size:14px;font-weight:600;padding:14px 28px;border-radius:100px;"
            >
                {{ content.button_text }}
            </a>
        </div>
    </section>
</template>
