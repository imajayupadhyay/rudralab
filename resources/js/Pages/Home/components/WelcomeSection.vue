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
        style="max-width:1200px;margin:0 auto;padding:100px 32px;display:grid;grid-template-columns:0.42fr 0.58fr;gap:72px;align-items:start;"
        class="vgtl-two"
    >
        <div>
            <span
                style="font-size:12px;letter-spacing:0.24em;text-transform:uppercase;color:#234A3E;font-weight:600;"
            >{{ content.eyebrow }}</span>
            <h2
                style="font-family:'Cormorant Garamond',serif;font-weight:500;font-size:clamp(34px,4vw,52px);line-height:1.08;margin:20px 0 0;"
            >
                {{ content.title }}
            </h2>
        </div>

        <div style="display:flex;flex-direction:column;gap:20px;">
            <p
                v-for="paragraph in content.paragraphs"
                :key="paragraph"
                style="font-size:16px;line-height:1.75;color:#4A4842;margin:0;"
            >
                {{ paragraph }}
            </p>
        </div>
    </section>
</template>
