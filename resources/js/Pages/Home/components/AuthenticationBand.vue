<script setup>
defineProps({
    content: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <section style="background:#234A3E;color:#EDEAE2;">
        <div style="max-width:1200px;margin:0 auto;padding:80px 32px;">
            <div
                style="display:flex;align-items:baseline;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:44px;"
            >
                <h2
                    style="font-family:'Cormorant Garamond',serif;font-weight:500;font-size:clamp(30px,3.6vw,46px);margin:0;"
                >
                    {{ content.title }}
                </h2>
                <span style="font-size:13px;color:rgba(237,234,226,0.6);letter-spacing:0.04em;">
                    {{ content.subtitle }}
                </span>
            </div>

            <div
                style="display:grid;grid-template-columns:repeat(5,1fr);gap:1px;background:rgba(237,234,226,0.14);border:1px solid rgba(237,234,226,0.14);"
                class="vgtl-checks"
            >
                <div
                    v-for="item in content.items"
                    :key="item"
                    style="background:#234A3E;padding:30px 22px;"
                >
                    <span
                        style="display:grid;place-items:center;width:30px;height:30px;border:1px solid rgba(237,234,226,0.35);border-radius:50%;font-size:13px;"
                    >✓</span>
                    <div style="font-size:15px;font-weight:600;margin-top:20px;line-height:1.35;">
                        {{ item }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
