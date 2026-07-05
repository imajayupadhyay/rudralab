<script setup>
defineProps({
    content: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <section style="max-width:1200px;margin:0 auto;padding:80px 32px 20px;">
        <div
            style="display:inline-flex;align-items:center;gap:10px;padding:6px 14px;border:1px solid rgba(35,74,62,0.3);border-radius:100px;margin-bottom:24px;"
        >
            <span style="width:6px;height:6px;border-radius:50%;background:#234A3E;"></span>
            <span
                style="font-size:11px;letter-spacing:0.18em;text-transform:uppercase;color:#234A3E;font-weight:600;"
            >{{ content.badge }}</span>
        </div>

        <h1
            style="font-family:'Cormorant Garamond',serif;font-weight:500;font-size:clamp(40px,6vw,68px);line-height:1.02;letter-spacing:-0.01em;margin:0 0 20px;max-width:760px;"
        >
            {{ content.title }} <em style="font-style:italic;color:#234A3E;">{{ content.highlight }}</em>
        </h1>

        <p style="font-size:16.5px;line-height:1.7;color:#4A4842;max-width:560px;margin:0;">
            {{ content.description }}
        </p>
    </section>
</template>
