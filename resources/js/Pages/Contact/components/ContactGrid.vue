<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
    info: {
        type: Array,
        required: true,
    },
    content: {
        type: Object,
        required: true,
    },
    addressCard: {
        type: Object,
        required: true,
    },
    sent: {
        type: Boolean,
        default: false,
    },
    processing: {
        type: Boolean,
        default: false,
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(['submit']);
</script>

<template>
    <section
        id="form"
        style="max-width:1200px;margin:0 auto;padding:56px 32px 40px;display:grid;grid-template-columns:0.92fr 1.08fr;gap:56px;align-items:start;"
        class="vgtl-contact-grid"
    >
        <div>
            <div
                style="display:grid;grid-template-columns:1fr 1fr;gap:1px;background:rgba(28,27,25,0.1);border:1px solid rgba(28,27,25,0.1);border-radius:16px;overflow:hidden;"
                class="vgtl-info-grid"
            >
                <div
                    v-for="item in info"
                    :key="item.label"
                    style="background:#FBFAF7;padding:28px 26px;"
                >
                    <div style="font-size:14px;color:#234A3E;margin-bottom:14px;font-weight:700;">{{ item.icon }}</div>
                    <div
                        style="font-size:11px;letter-spacing:0.16em;text-transform:uppercase;color:#9A968D;margin-bottom:10px;"
                    >
                        {{ item.label }}
                    </div>
                    <div style="font-size:14.5px;line-height:1.6;color:#1C1B19;white-space:pre-line;">
                        {{ item.value }}
                    </div>
                </div>
            </div>

            <div style="margin-top:24px;border:1px solid rgba(28,27,25,0.1);border-radius:16px;overflow:hidden;background:#234A3E;color:#EDEAE2;min-height:240px;display:grid;place-items:center;padding:34px;text-align:center;">
                <div>
                    <div style="display:grid;place-items:center;width:56px;height:56px;border:1px solid rgba(237,234,226,0.3);border-radius:50%;margin:0 auto 18px;font-family:'Cormorant Garamond',serif;font-size:24px;">{{ addressCard.mark }}</div>
                    <div style="font-size:11px;letter-spacing:0.16em;text-transform:uppercase;color:rgba(237,234,226,0.62);margin-bottom:10px;">{{ addressCard.label }}</div>
                    <div style="font-size:15px;line-height:1.7;max-width:360px;">{{ addressCard.address }}</div>
                </div>
            </div>
        </div>

        <div
            style="background:#FFFFFF;border:1px solid rgba(28,27,25,0.1);border-radius:20px;padding:40px;box-shadow:0 24px 60px rgba(35,74,62,0.06);"
        >
            <h2 style="font-family:'Cormorant Garamond',serif;font-weight:600;font-size:28px;margin:0 0 6px;">
                {{ content.title }}
            </h2>
            <p style="font-size:14px;color:#6B6862;margin:0 0 28px;line-height:1.6;">
                {{ content.description }}
            </p>

            <div
                v-if="sent"
                style="background:#EEF3F0;border:1px solid rgba(35,74,62,0.25);border-radius:12px;padding:18px 20px;display:flex;align-items:center;gap:12px;margin-bottom:24px;"
            >
                <span
                    style="display:grid;place-items:center;width:30px;height:30px;border-radius:50%;background:#234A3E;color:#F6F4EF;font-size:14px;flex-shrink:0;"
                >✓</span>
                <span style="font-size:14px;color:#234A3E;line-height:1.5;">
                    {{ content.success_message }}
                </span>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;" class="vgtl-name-row">
                <div>
                    <label
                        style="display:block;font-size:12px;letter-spacing:0.12em;text-transform:uppercase;color:#6B6862;font-weight:600;margin-bottom:8px;"
                    >{{ content.name_label }}</label>
                    <input
                        v-model="form.name"
                        class="vgtl-input"
                        :placeholder="content.name_placeholder"
                        style="width:100%;font-family:'Manrope',sans-serif;font-size:15px;color:#1C1B19;padding:13px 15px;border:1px solid rgba(28,27,25,0.18);border-radius:10px;background:#FBFAF7;transition:box-shadow .15s,border-color .15s;"
                    />
                    <div v-if="errors.name" style="font-size:12.5px;color:#B24A3E;margin-top:6px;">{{ errors.name }}</div>
                </div>
                <div>
                    <label
                        style="display:block;font-size:12px;letter-spacing:0.12em;text-transform:uppercase;color:#6B6862;font-weight:600;margin-bottom:8px;"
                    >{{ content.phone_label }}</label>
                    <input
                        v-model="form.phone"
                        class="vgtl-input"
                        :placeholder="content.phone_placeholder"
                        style="width:100%;font-family:'Manrope',sans-serif;font-size:15px;color:#1C1B19;padding:13px 15px;border:1px solid rgba(28,27,25,0.18);border-radius:10px;background:#FBFAF7;transition:box-shadow .15s,border-color .15s;"
                    />
                    <div v-if="errors.phone" style="font-size:12.5px;color:#B24A3E;margin-top:6px;">{{ errors.phone }}</div>
                </div>
            </div>

            <div style="margin-bottom:16px;">
                <label
                    style="display:block;font-size:12px;letter-spacing:0.12em;text-transform:uppercase;color:#6B6862;font-weight:600;margin-bottom:8px;"
                >{{ content.email_label }}</label>
                <input
                    v-model="form.email"
                    class="vgtl-input"
                    :placeholder="content.email_placeholder"
                    style="width:100%;font-family:'Manrope',sans-serif;font-size:15px;color:#1C1B19;padding:13px 15px;border:1px solid rgba(28,27,25,0.18);border-radius:10px;background:#FBFAF7;transition:box-shadow .15s,border-color .15s;"
                />
                <div v-if="errors.email" style="font-size:12.5px;color:#B24A3E;margin-top:6px;">{{ errors.email }}</div>
            </div>

            <div style="margin-bottom:24px;">
                <label
                    style="display:block;font-size:12px;letter-spacing:0.12em;text-transform:uppercase;color:#6B6862;font-weight:600;margin-bottom:8px;"
                >{{ content.message_label }}</label>
                <textarea
                    v-model="form.message"
                    class="vgtl-area"
                    :placeholder="content.message_placeholder"
                    rows="4"
                    style="width:100%;font-family:'Manrope',sans-serif;font-size:15px;color:#1C1B19;padding:13px 15px;border:1px solid rgba(28,27,25,0.18);border-radius:10px;background:#FBFAF7;resize:vertical;transition:box-shadow .15s,border-color .15s;"
                ></textarea>
                <div v-if="errors.message" style="font-size:12.5px;color:#B24A3E;margin-top:6px;">{{ errors.message }}</div>
            </div>

            <button
                style="width:100%;font-family:'Manrope',sans-serif;background:#234A3E;color:#F6F4EF;font-size:15px;font-weight:600;padding:16px;border:none;border-radius:12px;cursor:pointer;"
                type="button"
                :disabled="processing"
                @click="emit('submit')"
            >
                {{ processing ? 'Sending...' : content.button_text }}
            </button>
        </div>
    </section>
</template>
