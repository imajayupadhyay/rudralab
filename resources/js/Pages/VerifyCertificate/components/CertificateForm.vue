<script setup>
defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    showHint: {
        type: Boolean,
        default: false,
    },
    showTry: {
        type: Boolean,
        default: false,
    },
    content: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['update:modelValue', 'verify', 'fillSample']);
</script>

<template>
    <section style="max-width:720px;margin:0 auto;padding:0 32px 40px;">
        <div
            style="background:#FFFFFF;border:1px solid rgba(28,27,25,0.1);border-radius:20px;padding:40px;box-shadow:0 24px 60px rgba(35,74,62,0.06);"
        >
            <label
                style="display:block;font-size:12px;letter-spacing:0.16em;text-transform:uppercase;color:#6B6862;font-weight:600;margin-bottom:14px;"
            >{{ content.label }}</label>

            <div style="display:flex;gap:14px;align-items:stretch;" class="vgtl-form-row">
                <input
                    class="vgtl-input"
                    :value="modelValue"
                    :placeholder="content.placeholder"
                    style="flex:1;font-family:'Manrope',sans-serif;font-size:16px;letter-spacing:0.02em;color:#1C1B19;padding:16px 18px;border:1px solid rgba(28,27,25,0.18);border-radius:12px;background:#FBFAF7;transition:box-shadow .15s,border-color .15s;"
                    @input="emit('update:modelValue', $event.target.value)"
                    @keydown.enter="emit('verify')"
                />
                <button
                    style="font-family:'Manrope',sans-serif;background:#234A3E;color:#F6F4EF;font-size:15px;font-weight:600;padding:16px 34px;border:none;border-radius:12px;cursor:pointer;white-space:nowrap;"
                    type="button"
                    @click="emit('verify')"
                >
                    {{ content.button_text }}
                </button>
            </div>

            <p
                v-if="showHint"
                style="font-size:13px;color:#B24A3E;margin:14px 0 0;display:flex;align-items:center;gap:8px;"
            >
                <span>!</span> {{ content.empty_message }}
            </p>

            <p
                v-if="showTry"
                style="font-size:12.5px;color:#9A968D;margin:16px 0 0;"
            >
                {{ content.demo_prefix }}
                <button
                    style="background:none;border:none;padding:0;color:#234A3E;font-weight:600;font-family:inherit;font-size:12.5px;cursor:pointer;text-decoration:underline;"
                    type="button"
                    @click="emit('fillSample')"
                >
                    {{ content.demo_certificate }}
                </button>
            </p>
        </div>
    </section>
</template>
