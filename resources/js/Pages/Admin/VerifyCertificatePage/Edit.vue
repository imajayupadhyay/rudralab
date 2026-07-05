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
        .post('/rbtl/verify-certificate-page', {
            preserveScroll: true,
        });
};

const addHelp = () => {
    form.content.help.push({ n: String(form.content.help.length + 1).padStart(2, '0'), t: '' });
};

const removeHelp = (index) => form.content.help.splice(index, 1);
</script>

<template>
    <Head title="Manage Verify Certificate Page" />

    <AdminLayout title="Verify Page" eyebrow="Content Management">
        <form class="rbtl-verify-editor" @submit.prevent="submit">
            <div class="rbtl-verify-actions">
                <div>
                    <span>Public Page</span>
                    <h2>Verify certificate content</h2>
                </div>
                <button type="submit" :disabled="form.processing">
                    {{ form.processing ? 'Saving...' : 'Save Verify Page' }}
                </button>
            </div>

            <section class="rbtl-admin-card rbtl-verify-section">
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

            <section class="rbtl-admin-card rbtl-verify-section">
                <header>
                    <span>Section 02</span>
                    <h3>Search Form</h3>
                </header>

                <div class="rbtl-form-grid">
                    <label>
                        <span>Input Label</span>
                        <input v-model="form.content.form.label" type="text" />
                    </label>
                    <label>
                        <span>Placeholder</span>
                        <input v-model="form.content.form.placeholder" type="text" />
                    </label>
                    <label>
                        <span>Button Text</span>
                        <input v-model="form.content.form.button_text" type="text" />
                    </label>
                    <label>
                        <span>Empty Message</span>
                        <input v-model="form.content.form.empty_message" type="text" />
                    </label>
                    <label>
                        <span>Demo Prefix</span>
                        <input v-model="form.content.form.demo_prefix" type="text" />
                    </label>
                    <label>
                        <span>Demo Certificate</span>
                        <input v-model="form.content.form.demo_certificate" type="text" />
                    </label>
                </div>
            </section>

            <section class="rbtl-admin-card rbtl-verify-section">
                <header>
                    <span>Section 03</span>
                    <h3>Result Messages</h3>
                </header>

                <div class="rbtl-form-grid">
                    <label>
                        <span>Verified Label</span>
                        <input v-model="form.content.result.verified_label" type="text" />
                    </label>
                    <label>
                        <span>Issued Prefix</span>
                        <input v-model="form.content.result.issued_prefix" type="text" />
                    </label>
                    <label>
                        <span>Result Image Alt</span>
                        <input v-model="form.content.result.image_alt" type="text" />
                    </label>
                    <label>
                        <span>Not Found Title</span>
                        <input v-model="form.content.result.not_found_title" type="text" />
                    </label>
                    <label class="wide">
                        <span>Registry Note</span>
                        <textarea v-model="form.content.result.registry_note" rows="3"></textarea>
                    </label>
                    <label>
                        <span>Not Found Prefix</span>
                        <input v-model="form.content.result.not_found_prefix" type="text" />
                    </label>
                    <label>
                        <span>Not Found Suffix</span>
                        <input v-model="form.content.result.not_found_suffix" type="text" />
                    </label>
                </div>
            </section>

            <section class="rbtl-admin-card rbtl-verify-section">
                <header>
                    <span>Section 04</span>
                    <h3>Certificate Field Labels</h3>
                </header>

                <div class="rbtl-form-grid">
                    <label>
                        <span>Certificate</span>
                        <input v-model="form.content.result.field_labels.certificate" type="text" />
                    </label>
                    <label>
                        <span>Weight</span>
                        <input v-model="form.content.result.field_labels.weight" type="text" />
                    </label>
                    <label>
                        <span>Shape/Cut</span>
                        <input v-model="form.content.result.field_labels.shape_cut" type="text" />
                    </label>
                    <label>
                        <span>Dimension</span>
                        <input v-model="form.content.result.field_labels.dimension" type="text" />
                    </label>
                    <label>
                        <span>Colour</span>
                        <input v-model="form.content.result.field_labels.colour" type="text" />
                    </label>
                    <label>
                        <span>Refractive Index</span>
                        <input v-model="form.content.result.field_labels.refractive_index" type="text" />
                    </label>
                    <label>
                        <span>Specific Gravity</span>
                        <input v-model="form.content.result.field_labels.specific_gravity" type="text" />
                    </label>
                    <label>
                        <span>Origin</span>
                        <input v-model="form.content.result.field_labels.origin" type="text" />
                    </label>
                    <label>
                        <span>Issued To</span>
                        <input v-model="form.content.result.field_labels.issued_to" type="text" />
                    </label>
                    <label>
                        <span>Remarks</span>
                        <input v-model="form.content.result.field_labels.remarks" type="text" />
                    </label>
                </div>
            </section>

            <section class="rbtl-admin-card rbtl-verify-section">
                <header>
                    <span>Section 05</span>
                    <h3>Certificate Preview</h3>
                </header>

                <div class="rbtl-form-grid">
                    <label>
                        <span>Logo Text</span>
                        <input v-model="form.content.certificate_preview.logo_text" type="text" />
                    </label>
                    <label>
                        <span>Brand Title</span>
                        <input v-model="form.content.certificate_preview.brand_title" type="text" />
                    </label>
                    <label>
                        <span>Brand Tagline</span>
                        <input v-model="form.content.certificate_preview.brand_tagline" type="text" />
                    </label>
                    <label>
                        <span>Report Title</span>
                        <input v-model="form.content.certificate_preview.report_title" type="text" />
                    </label>
                    <label>
                        <span>QR Alt Text</span>
                        <input v-model="form.content.certificate_preview.qr_alt" type="text" />
                    </label>
                    <label>
                        <span>Item Image Alt</span>
                        <input v-model="form.content.certificate_preview.item_alt" type="text" />
                    </label>
                    <label>
                        <span>Signature Name</span>
                        <input v-model="form.content.certificate_preview.signature_name" type="text" />
                    </label>
                    <label>
                        <span>Signature Label</span>
                        <input v-model="form.content.certificate_preview.signature_label" type="text" />
                    </label>
                </div>
            </section>

            <section class="rbtl-admin-card rbtl-verify-section">
                <header>
                    <span>Section 06</span>
                    <h3>Help Steps</h3>
                </header>

                <div class="rbtl-help-list">
                    <div v-for="(help, index) in form.content.help" :key="index" class="rbtl-help-row">
                        <label>
                            <span>Number</span>
                            <input v-model="help.n" type="text" />
                        </label>
                        <label>
                            <span>Text</span>
                            <input v-model="help.t" type="text" />
                        </label>
                        <button type="button" @click="removeHelp(index)">Remove</button>
                    </div>
                </div>
                <button class="rbtl-small-action" type="button" @click="addHelp">Add Help Step</button>
            </section>

            <div class="rbtl-verify-bottom-actions">
                <button type="submit" :disabled="form.processing">
                    {{ form.processing ? 'Saving...' : 'Save Verify Page' }}
                </button>
            </div>
        </form>
    </AdminLayout>
</template>

<style>
.rbtl-verify-editor {
    display: flex;
    flex-direction: column;
    gap: 22px;
}

.rbtl-verify-actions,
.rbtl-verify-bottom-actions {
    align-items: center;
    display: flex;
    justify-content: space-between;
}

.rbtl-verify-actions span,
.rbtl-verify-section header span,
.rbtl-verify-section label > span {
    color: #6b6862;
    display: block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
}

.rbtl-verify-actions h2,
.rbtl-verify-section h3 {
    font-family: 'Cormorant Garamond', serif;
    line-height: 1;
    margin: 6px 0 0;
}

.rbtl-verify-actions h2 {
    font-size: 34px;
}

.rbtl-verify-section h3 {
    font-size: 30px;
}

.rbtl-verify-editor button[type='submit'],
.rbtl-small-action,
.rbtl-help-row button {
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font: inherit;
    font-size: 13px;
    font-weight: 800;
    padding: 11px 15px;
}

.rbtl-verify-editor button[type='submit'],
.rbtl-small-action {
    background: #234a3e;
    color: #f6f4ef;
}

.rbtl-verify-editor button:disabled {
    cursor: not-allowed;
    opacity: 0.72;
}

.rbtl-verify-section {
    padding: 24px;
}

.rbtl-verify-section header {
    margin-bottom: 20px;
}

.rbtl-form-grid {
    display: grid;
    gap: 16px;
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.rbtl-form-grid label,
.rbtl-help-row label {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.rbtl-form-grid .wide {
    grid-column: 1 / -1;
}

.rbtl-verify-section input,
.rbtl-verify-section textarea {
    background: #fbfaf7;
    border: 1px solid rgba(28, 27, 25, 0.14);
    border-radius: 8px;
    color: #1c1b19;
    font: inherit;
    padding: 12px 14px;
}

.rbtl-verify-section textarea {
    resize: vertical;
}

.rbtl-verify-section input:focus,
.rbtl-verify-section textarea:focus {
    border-color: #234a3e;
    box-shadow: 0 0 0 3px rgba(35, 74, 62, 0.12);
    outline: none;
}

.rbtl-help-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.rbtl-help-row {
    align-items: end;
    border: 1px solid rgba(28, 27, 25, 0.08);
    border-radius: 8px;
    display: grid;
    gap: 12px;
    grid-template-columns: 100px minmax(0, 1fr) auto;
    padding: 14px;
}

.rbtl-help-row button {
    background: #b24a3e;
    color: #ffffff;
}

.rbtl-small-action {
    margin-top: 16px;
}

.rbtl-verify-bottom-actions {
    justify-content: flex-end;
}

@media (max-width: 840px) {
    .rbtl-verify-actions,
    .rbtl-verify-bottom-actions {
        align-items: flex-start;
        flex-direction: column;
        gap: 14px;
    }

    .rbtl-form-grid,
    .rbtl-help-row {
        grid-template-columns: 1fr;
    }
}
</style>
