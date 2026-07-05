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
    sent: {
        type: Boolean,
        default: false,
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
                    <div style="font-size:20px;color:#234A3E;margin-bottom:14px;">{{ item.icon }}</div>
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
                    <div style="display:grid;place-items:center;width:56px;height:56px;border:1px solid rgba(237,234,226,0.3);border-radius:50%;margin:0 auto 18px;font-family:'Cormorant Garamond',serif;font-size:24px;">R</div>
                    <div style="font-size:11px;letter-spacing:0.16em;text-transform:uppercase;color:rgba(237,234,226,0.62);margin-bottom:10px;">RBTL Lab Address</div>
                    <div style="font-size:15px;line-height:1.7;max-width:360px;">Shiv Shakti Traders, near main Shaheed Udham Chowk, Guhla Road, Cheeka, Haryana</div>
                </div>
            </div>
        </div>

        <div
            style="background:#FFFFFF;border:1px solid rgba(28,27,25,0.1);border-radius:20px;padding:40px;box-shadow:0 24px 60px rgba(35,74,62,0.06);"
        >
            <h2 style="font-family:'Cormorant Garamond',serif;font-weight:600;font-size:28px;margin:0 0 6px;">
                Send us a message
            </h2>
            <p style="font-size:14px;color:#6B6862;margin:0 0 28px;line-height:1.6;">
                Fill in your details and the RBTL team will get back to you shortly.
            </p>

            <div
                v-if="sent"
                style="background:#EEF3F0;border:1px solid rgba(35,74,62,0.25);border-radius:12px;padding:18px 20px;display:flex;align-items:center;gap:12px;margin-bottom:24px;"
            >
                <span
                    style="display:grid;place-items:center;width:30px;height:30px;border-radius:50%;background:#234A3E;color:#F6F4EF;font-size:14px;flex-shrink:0;"
                >✓</span>
                <span style="font-size:14px;color:#234A3E;line-height:1.5;">
                    Thank you, your message has been noted. We'll reach out soon.
                </span>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;" class="vgtl-name-row">
                <div>
                    <label
                        style="display:block;font-size:12px;letter-spacing:0.12em;text-transform:uppercase;color:#6B6862;font-weight:600;margin-bottom:8px;"
                    >Name</label>
                    <input
                        v-model="form.name"
                        class="vgtl-input"
                        placeholder="Your name"
                        style="width:100%;font-family:'Manrope',sans-serif;font-size:15px;color:#1C1B19;padding:13px 15px;border:1px solid rgba(28,27,25,0.18);border-radius:10px;background:#FBFAF7;transition:box-shadow .15s,border-color .15s;"
                    />
                </div>
                <div>
                    <label
                        style="display:block;font-size:12px;letter-spacing:0.12em;text-transform:uppercase;color:#6B6862;font-weight:600;margin-bottom:8px;"
                    >Phone</label>
                    <input
                        v-model="form.phone"
                        class="vgtl-input"
                        placeholder="+91 …"
                        style="width:100%;font-family:'Manrope',sans-serif;font-size:15px;color:#1C1B19;padding:13px 15px;border:1px solid rgba(28,27,25,0.18);border-radius:10px;background:#FBFAF7;transition:box-shadow .15s,border-color .15s;"
                    />
                </div>
            </div>

            <div style="margin-bottom:16px;">
                <label
                    style="display:block;font-size:12px;letter-spacing:0.12em;text-transform:uppercase;color:#6B6862;font-weight:600;margin-bottom:8px;"
                >Email</label>
                <input
                    v-model="form.email"
                    class="vgtl-input"
                    placeholder="you@example.com"
                    style="width:100%;font-family:'Manrope',sans-serif;font-size:15px;color:#1C1B19;padding:13px 15px;border:1px solid rgba(28,27,25,0.18);border-radius:10px;background:#FBFAF7;transition:box-shadow .15s,border-color .15s;"
                />
            </div>

            <div style="margin-bottom:24px;">
                <label
                    style="display:block;font-size:12px;letter-spacing:0.12em;text-transform:uppercase;color:#6B6862;font-weight:600;margin-bottom:8px;"
                >Message</label>
                <textarea
                    v-model="form.message"
                    class="vgtl-area"
                    placeholder="Tell us about the Rudra bead you'd like certified…"
                    rows="4"
                    style="width:100%;font-family:'Manrope',sans-serif;font-size:15px;color:#1C1B19;padding:13px 15px;border:1px solid rgba(28,27,25,0.18);border-radius:10px;background:#FBFAF7;resize:vertical;transition:box-shadow .15s,border-color .15s;"
                ></textarea>
            </div>

            <button
                style="width:100%;font-family:'Manrope',sans-serif;background:#234A3E;color:#F6F4EF;font-size:15px;font-weight:600;padding:16px;border:none;border-radius:12px;cursor:pointer;"
                type="button"
                @click="emit('submit')"
            >
                Send Message
            </button>
        </div>
    </section>
</template>
