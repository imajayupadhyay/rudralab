<script setup>
defineProps({
    services: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <section style="max-width:1200px;margin:0 auto;padding:20px 32px 110px;">
        <div style="text-align:center;margin-bottom:56px;">
            <span
                style="font-size:12px;letter-spacing:0.24em;text-transform:uppercase;color:#234A3E;font-weight:600;"
            >Our Services</span>
            <h2
                style="font-family:'Cormorant Garamond',serif;font-weight:500;font-size:clamp(34px,4vw,52px);line-height:1.08;margin:18px 0 0;"
            >
                Certified with scientific rigor.
            </h2>
        </div>

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:28px;" class="vgtl-cards">
            <div
                v-for="service in services"
                :key="service.title"
                style="border:1px solid rgba(28,27,25,0.1);border-radius:16px;overflow:hidden;background:#FFFFFF;display:flex;flex-direction:column;"
            >
                <div style="aspect-ratio:16/10;background:#EFEDE7;overflow:hidden;">
                    <img
                        :src="service.img"
                        :alt="service.title"
                        style="width:100%;height:100%;object-fit:cover;display:block;"
                    />
                </div>
                <div style="padding:30px 28px 34px;display:flex;flex-direction:column;flex:1;">
                    <h3
                        style="font-family:'Cormorant Garamond',serif;font-weight:600;font-size:24px;margin:0 0 12px;"
                    >
                        {{ service.title }}
                    </h3>
                    <p style="font-size:14.5px;line-height:1.65;color:#6B6862;margin:0 0 22px;flex:1;">
                        {{ service.desc }}
                    </p>
                    <a
                        href="/contact"
                        style="text-decoration:none;color:#234A3E;font-size:13.5px;font-weight:600;display:inline-flex;align-items:center;gap:8px;"
                    >Learn more <span style="font-family:serif;">→</span></a>
                </div>
            </div>
        </div>
    </section>
</template>
