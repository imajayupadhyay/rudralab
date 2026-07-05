<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    content: {
        type: Object,
        required: true,
    },
});

const clone = (value) => JSON.parse(JSON.stringify(value));

const form = useForm({
    content: clone(props.content),
});

const submit = () => {
    form
        .transform((data) => ({
            ...data,
            _method: 'put',
        }))
        .post('/rbtl/contact-page', {
            preserveScroll: true,
        });
};

const addInfo = () => form.content.info.push({ icon: '', label: '', value: '' });
const removeInfo = (index) => form.content.info.splice(index, 1);
</script>

<template>
    <Head title="Manage Contact Page" />

    <AdminLayout title="Contact Page" eyebrow="Content Management">
        <form class="rbtl-contact-editor" @submit.prevent="submit">
            <div class="rbtl-contact-actions">
                <div>
                    <span>Public Page</span>
                    <h2>Contact page content</h2>
                </div>
                <button type="submit" :disabled="form.processing">
                    {{ form.processing ? 'Saving...' : 'Save Contact Page' }}
                </button>
            </div>

            <section class="rbtl-admin-card rbtl-contact-section">
                <header>
                    <span>Section 01</span>
                    <h3>Hero</h3>
                </header>

                <div class="rbtl-form-grid">
                    <label>
                        <span>Badge</span>
                        <input v-model="form.content.hero.badge" type="text" />
                    </label>
                    <label>
                        <span>Heading</span>
                        <input v-model="form.content.hero.title" type="text" />
                    </label>
                    <label>
                        <span>Highlighted Heading</span>
                        <input v-model="form.content.hero.highlight" type="text" />
                    </label>
                    <label class="wide">
                        <span>Description</span>
                        <textarea v-model="form.content.hero.description" rows="4"></textarea>
                    </label>
                </div>
            </section>

            <section class="rbtl-admin-card rbtl-contact-section">
                <header>
                    <span>Section 02</span>
                    <h3>Contact Info Cards</h3>
                </header>

                <div class="rbtl-info-list">
                    <div v-for="(item, index) in form.content.info" :key="index" class="rbtl-info-row">
                        <label>
                            <span>Icon Text</span>
                            <input v-model="item.icon" type="text" />
                        </label>
                        <label>
                            <span>Label</span>
                            <input v-model="item.label" type="text" />
                        </label>
                        <label class="wide">
                            <span>Value</span>
                            <textarea v-model="item.value" rows="3"></textarea>
                        </label>
                        <button type="button" @click="removeInfo(index)">Remove</button>
                    </div>
                </div>
                <button class="rbtl-small-action" type="button" @click="addInfo">Add Info Card</button>
            </section>

            <section class="rbtl-admin-card rbtl-contact-section">
                <header>
                    <span>Section 03</span>
                    <h3>Address Card</h3>
                </header>

                <div class="rbtl-form-grid">
                    <label>
                        <span>Mark</span>
                        <input v-model="form.content.address_card.mark" type="text" />
                    </label>
                    <label>
                        <span>Label</span>
                        <input v-model="form.content.address_card.label" type="text" />
                    </label>
                    <label class="wide">
                        <span>Address</span>
                        <textarea v-model="form.content.address_card.address" rows="3"></textarea>
                    </label>
                </div>
            </section>

            <section class="rbtl-admin-card rbtl-contact-section">
                <header>
                    <span>Section 04</span>
                    <h3>Contact Form Text</h3>
                </header>

                <div class="rbtl-form-grid">
                    <label>
                        <span>Form Heading</span>
                        <input v-model="form.content.form.title" type="text" />
                    </label>
                    <label>
                        <span>Button Text</span>
                        <input v-model="form.content.form.button_text" type="text" />
                    </label>
                    <label class="wide">
                        <span>Form Description</span>
                        <textarea v-model="form.content.form.description" rows="3"></textarea>
                    </label>
                    <label class="wide">
                        <span>Success Message</span>
                        <textarea v-model="form.content.form.success_message" rows="3"></textarea>
                    </label>
                    <label>
                        <span>Name Label</span>
                        <input v-model="form.content.form.name_label" type="text" />
                    </label>
                    <label>
                        <span>Name Placeholder</span>
                        <input v-model="form.content.form.name_placeholder" type="text" />
                    </label>
                    <label>
                        <span>Phone Label</span>
                        <input v-model="form.content.form.phone_label" type="text" />
                    </label>
                    <label>
                        <span>Phone Placeholder</span>
                        <input v-model="form.content.form.phone_placeholder" type="text" />
                    </label>
                    <label>
                        <span>Email Label</span>
                        <input v-model="form.content.form.email_label" type="text" />
                    </label>
                    <label>
                        <span>Email Placeholder</span>
                        <input v-model="form.content.form.email_placeholder" type="text" />
                    </label>
                    <label>
                        <span>Message Label</span>
                        <input v-model="form.content.form.message_label" type="text" />
                    </label>
                    <label>
                        <span>Message Placeholder</span>
                        <input v-model="form.content.form.message_placeholder" type="text" />
                    </label>
                </div>
            </section>

            <section class="rbtl-admin-card rbtl-contact-section">
                <header>
                    <span>Section 05</span>
                    <h3>Hours Banner</h3>
                </header>

                <div class="rbtl-form-grid">
                    <label>
                        <span>Icon Text</span>
                        <input v-model="form.content.hours.icon" type="text" />
                    </label>
                    <label>
                        <span>Label</span>
                        <input v-model="form.content.hours.label" type="text" />
                    </label>
                    <label>
                        <span>Hours Text</span>
                        <input v-model="form.content.hours.text" type="text" />
                    </label>
                    <label>
                        <span>Button Text</span>
                        <input v-model="form.content.hours.button_text" type="text" />
                    </label>
                    <label>
                        <span>Button URL</span>
                        <input v-model="form.content.hours.button_url" type="text" />
                    </label>
                </div>
            </section>

            <div class="rbtl-contact-bottom-actions">
                <button type="submit" :disabled="form.processing">
                    {{ form.processing ? 'Saving...' : 'Save Contact Page' }}
                </button>
            </div>
        </form>
    </AdminLayout>
</template>

<style>
.rbtl-contact-editor {
    display: flex;
    flex-direction: column;
    gap: 22px;
}

.rbtl-contact-actions,
.rbtl-contact-bottom-actions {
    align-items: center;
    display: flex;
    justify-content: space-between;
}

.rbtl-contact-actions span,
.rbtl-contact-section header span,
.rbtl-contact-section label > span {
    color: #6b6862;
    display: block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
}

.rbtl-contact-actions h2,
.rbtl-contact-section h3 {
    font-family: 'Cormorant Garamond', serif;
    line-height: 1;
    margin: 6px 0 0;
}

.rbtl-contact-actions h2 {
    font-size: 34px;
}

.rbtl-contact-section h3 {
    font-size: 30px;
}

.rbtl-contact-editor button[type='submit'],
.rbtl-small-action,
.rbtl-info-row button {
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font: inherit;
    font-size: 13px;
    font-weight: 800;
    padding: 11px 15px;
}

.rbtl-contact-editor button[type='submit'],
.rbtl-small-action {
    background: #234a3e;
    color: #f6f4ef;
}

.rbtl-contact-editor button:disabled {
    cursor: not-allowed;
    opacity: 0.72;
}

.rbtl-contact-section {
    padding: 24px;
}

.rbtl-contact-section header {
    margin-bottom: 20px;
}

.rbtl-form-grid {
    display: grid;
    gap: 16px;
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.rbtl-form-grid label,
.rbtl-info-row label {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.rbtl-form-grid .wide,
.rbtl-info-row .wide {
    grid-column: 1 / -1;
}

.rbtl-contact-section input,
.rbtl-contact-section textarea {
    background: #fbfaf7;
    border: 1px solid rgba(28, 27, 25, 0.14);
    border-radius: 8px;
    color: #1c1b19;
    font: inherit;
    padding: 12px 14px;
}

.rbtl-contact-section textarea {
    resize: vertical;
}

.rbtl-contact-section input:focus,
.rbtl-contact-section textarea:focus {
    border-color: #234a3e;
    box-shadow: 0 0 0 3px rgba(35, 74, 62, 0.12);
    outline: none;
}

.rbtl-info-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.rbtl-info-row {
    align-items: end;
    border: 1px solid rgba(28, 27, 25, 0.08);
    border-radius: 8px;
    display: grid;
    gap: 12px;
    grid-template-columns: 180px minmax(0, 1fr) auto;
    padding: 14px;
}

.rbtl-info-row button {
    background: #b24a3e;
    color: #ffffff;
}

.rbtl-small-action {
    margin-top: 16px;
}

.rbtl-contact-bottom-actions {
    justify-content: flex-end;
}

@media (max-width: 840px) {
    .rbtl-contact-actions,
    .rbtl-contact-bottom-actions {
        align-items: flex-start;
        flex-direction: column;
        gap: 14px;
    }

    .rbtl-form-grid,
    .rbtl-info-row {
        grid-template-columns: 1fr;
    }
}
</style>
