<script setup>
defineProps({
    stats: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <section style="border-top:1px solid rgba(28,27,25,0.09);border-bottom:1px solid rgba(28,27,25,0.09);">
        <div
            style="max-width:1200px;margin:0 auto;padding:0 32px;display:grid;grid-template-columns:repeat(4,1fr);"
            class="vgtl-stats"
        >
            <div
                v-for="item in stats"
                :key="item.label"
                style="padding:34px 24px;border-left:1px solid rgba(28,27,25,0.09);"
            >
                <div
                    style="font-family:'Cormorant Garamond',serif;font-size:42px;font-weight:500;color:#234A3E;line-height:1;"
                >
                    {{ item.value }}
                </div>
                <div style="font-size:13px;color:#6B6862;margin-top:10px;letter-spacing:0.02em;">
                    {{ item.label }}
                </div>
            </div>
        </div>
    </section>
</template>
