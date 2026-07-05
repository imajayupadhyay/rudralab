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
        style="max-width:1200px;margin:0 auto;padding:100px 32px 60px;display:grid;grid-template-columns:0.55fr 0.45fr;gap:64px;align-items:center;"
        class="vgtl-two"
    >
        <div>
            <span
                style="font-size:12px;letter-spacing:0.24em;text-transform:uppercase;color:#234A3E;font-weight:600;"
            >{{ content.eyebrow }}</span>
            <h2
                style="font-family:'Cormorant Garamond',serif;font-weight:500;font-size:clamp(34px,4vw,52px);line-height:1.08;margin:20px 0 22px;"
            >
                {{ content.title }}
            </h2>
            <p style="font-size:16px;line-height:1.75;color:#4A4842;margin:0;max-width:520px;">
                {{ content.description }}
            </p>
        </div>

        <div style="border:1px solid rgba(28,27,25,0.1);border-radius:14px;overflow:hidden;">
            <img
                :src="content.image"
                :alt="content.image_alt"
                style="width:100%;display:block;"
            />
        </div>
    </section>

    <section
        style="max-width:1200px;margin:0 auto;padding:20px 32px 100px;display:grid;grid-template-columns:1fr 1fr;gap:32px;"
        class="vgtl-two"
    >
        <div style="border:1px solid rgba(28,27,25,0.1);border-radius:16px;padding:44px;background:#FBFAF7;">
            <h3
                style="font-family:'Cormorant Garamond',serif;font-weight:600;font-size:28px;margin:0 0 24px;"
            >
                {{ content.mission_title }}
            </h3>
            <ul style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:16px;">
                <li
                    v-for="item in content.mission"
                    :key="item"
                    style="display:flex;gap:14px;font-size:15px;line-height:1.6;color:#4A4842;"
                >
                    <span style="color:#234A3E;flex-shrink:0;margin-top:1px;">➞</span>
                    <span>{{ item }}</span>
                </li>
            </ul>
        </div>

        <div style="border:1px solid rgba(28,27,25,0.1);border-radius:16px;padding:44px;background:#FBFAF7;">
            <h3
                style="font-family:'Cormorant Garamond',serif;font-weight:600;font-size:28px;margin:0 0 24px;"
            >
                {{ content.vision_title }}
            </h3>
            <ul style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:16px;">
                <li
                    v-for="item in content.vision"
                    :key="item"
                    style="display:flex;gap:14px;font-size:15px;line-height:1.6;color:#4A4842;"
                >
                    <span style="color:#234A3E;flex-shrink:0;margin-top:1px;">➞</span>
                    <span>{{ item }}</span>
                </li>
            </ul>
        </div>
    </section>
</template>
