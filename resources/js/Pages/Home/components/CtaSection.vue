<script setup>
defineProps({
    content: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <section style="max-width:1200px;margin:0 auto 100px;padding:0 32px;">
        <div
            style="background:#234A3E;border-radius:24px;padding:72px 48px;text-align:center;color:#EDEAE2;position:relative;overflow:hidden;"
        >
            <div
                style="position:absolute;width:340px;height:340px;border-radius:50%;border:1px solid rgba(237,234,226,0.14);top:-140px;right:-80px;"
            ></div>
            <div
                style="position:absolute;width:220px;height:220px;border-radius:50%;border:1px solid rgba(237,234,226,0.12);bottom:-110px;left:-40px;"
            ></div>
            <div style="position:relative;">
                <h2
                    style="font-family:'Cormorant Garamond',serif;font-weight:500;font-size:clamp(32px,4vw,50px);line-height:1.1;margin:0 0 18px;max-width:640px;margin-left:auto;margin-right:auto;"
                >
                    {{ content.title }}
                </h2>
                <p
                    style="font-size:16px;line-height:1.7;color:rgba(237,234,226,0.75);max-width:520px;margin:0 auto 34px;"
                >
                    {{ content.description }}
                </p>
                <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
                    <a
                        :href="content.primary_button_url"
                        style="text-decoration:none;background:#F6F4EF;color:#234A3E;font-size:14px;font-weight:600;padding:15px 32px;border-radius:100px;"
                    >{{ content.primary_button_text }}</a>
                    <a
                        :href="content.secondary_button_url"
                        style="text-decoration:none;border:1px solid rgba(237,234,226,0.4);color:#EDEAE2;font-size:14px;font-weight:600;padding:15px 32px;border-radius:100px;"
                    >{{ content.secondary_button_text }}</a>
                </div>
            </div>
        </div>
    </section>
</template>
