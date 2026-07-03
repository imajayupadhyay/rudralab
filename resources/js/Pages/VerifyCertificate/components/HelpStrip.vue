<script setup>
defineProps({
    helps: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <section style="max-width:720px;margin:0 auto;padding:20px 32px 90px;">
        <div
            style="display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:rgba(28,27,25,0.09);border:1px solid rgba(28,27,25,0.09);border-radius:16px;overflow:hidden;"
        >
            <div
                v-for="help in helps"
                :key="help.n"
                style="background:#F6F4EF;padding:26px 22px;text-align:center;"
            >
                <div style="font-family:'Cormorant Garamond',serif;font-size:22px;color:#234A3E;margin-bottom:8px;">
                    {{ help.n }}
                </div>
                <div style="font-size:13px;color:#6B6862;line-height:1.5;">
                    {{ help.t }}
                </div>
            </div>
        </div>
    </section>
</template>
