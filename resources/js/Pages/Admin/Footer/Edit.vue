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
        .post('/rbtl/footer', {
            preserveScroll: true,
        });
};

const addNavLink = () => form.content.navigation.links.push({ label: '', url: '/' });
const removeNavLink = (index) => form.content.navigation.links.splice(index, 1);
const addService = () => form.content.services.items.push({ label: '' });
const removeService = (index) => form.content.services.items.splice(index, 1);
</script>

<template>
    <Head title="Manage Footer" />

    <AdminLayout title="Footer" eyebrow="Content Management">
        <form class="rbtl-footer-editor" @submit.prevent="submit">
            <div class="rbtl-footer-actions">
                <div>
                    <span>Global Site Area</span>
                    <h2>Footer content</h2>
                </div>
                <button type="submit" :disabled="form.processing">
                    {{ form.processing ? 'Saving...' : 'Save Footer' }}
                </button>
            </div>

            <section class="rbtl-admin-card rbtl-footer-section">
                <header>
                    <span>Section 01</span>
                    <h3>Brand</h3>
                </header>

                <div class="rbtl-form-grid">
                    <label>
                        <span>Logo Mark</span>
                        <input v-model="form.content.brand.mark" type="text" />
                    </label>
                    <label>
                        <span>Brand Title</span>
                        <input v-model="form.content.brand.title" type="text" />
                    </label>
                    <label class="wide">
                        <span>Description</span>
                        <textarea v-model="form.content.brand.description" rows="4"></textarea>
                    </label>
                </div>
            </section>

            <section class="rbtl-admin-card rbtl-footer-section">
                <header>
                    <span>Section 02</span>
                    <h3>Navigation</h3>
                </header>

                <div class="rbtl-form-grid">
                    <label class="wide">
                        <span>Column Heading</span>
                        <input v-model="form.content.navigation.heading" type="text" />
                    </label>
                </div>

                <div class="rbtl-footer-list">
                    <div v-for="(link, index) in form.content.navigation.links" :key="index" class="rbtl-footer-row">
                        <label>
                            <span>Label</span>
                            <input v-model="link.label" type="text" />
                        </label>
                        <label>
                            <span>Internal URL</span>
                            <input v-model="link.url" type="text" placeholder="/contact" />
                            <small v-if="form.errors[`content.navigation.links.${index}.url`]">
                                {{ form.errors[`content.navigation.links.${index}.url`] }}
                            </small>
                        </label>
                        <button type="button" @click="removeNavLink(index)">Remove</button>
                    </div>
                </div>
                <button class="rbtl-small-action" type="button" @click="addNavLink">Add Navigation Link</button>
            </section>

            <section class="rbtl-admin-card rbtl-footer-section">
                <header>
                    <span>Section 03</span>
                    <h3>Services</h3>
                </header>

                <div class="rbtl-form-grid">
                    <label class="wide">
                        <span>Column Heading</span>
                        <input v-model="form.content.services.heading" type="text" />
                    </label>
                </div>

                <div class="rbtl-footer-list">
                    <div v-for="(item, index) in form.content.services.items" :key="index" class="rbtl-footer-row service">
                        <label>
                            <span>Service Label</span>
                            <input v-model="item.label" type="text" />
                        </label>
                        <button type="button" @click="removeService(index)">Remove</button>
                    </div>
                </div>
                <button class="rbtl-small-action" type="button" @click="addService">Add Service</button>
            </section>

            <section class="rbtl-admin-card rbtl-footer-section">
                <header>
                    <span>Section 04</span>
                    <h3>Bottom Bar</h3>
                </header>

                <div class="rbtl-form-grid">
                    <label>
                        <span>Copyright Text</span>
                        <input v-model="form.content.legal.copyright" type="text" />
                    </label>
                    <label>
                        <span>Tagline</span>
                        <input v-model="form.content.legal.tagline" type="text" />
                    </label>
                </div>
            </section>

            <div class="rbtl-footer-bottom-actions">
                <button type="submit" :disabled="form.processing">
                    {{ form.processing ? 'Saving...' : 'Save Footer' }}
                </button>
            </div>
        </form>
    </AdminLayout>
</template>

<style>
.rbtl-footer-editor {
    display: flex;
    flex-direction: column;
    gap: 22px;
}

.rbtl-footer-actions,
.rbtl-footer-bottom-actions {
    align-items: center;
    display: flex;
    justify-content: space-between;
}

.rbtl-footer-actions span,
.rbtl-footer-section header span,
.rbtl-footer-section label > span {
    color: #6b6862;
    display: block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
}

.rbtl-footer-actions h2,
.rbtl-footer-section h3 {
    font-family: 'Cormorant Garamond', serif;
    line-height: 1;
    margin: 6px 0 0;
}

.rbtl-footer-actions h2 {
    font-size: 34px;
}

.rbtl-footer-section h3 {
    font-size: 30px;
}

.rbtl-footer-editor button[type='submit'],
.rbtl-small-action,
.rbtl-footer-row button {
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font: inherit;
    font-size: 13px;
    font-weight: 800;
    padding: 11px 15px;
}

.rbtl-footer-editor button[type='submit'],
.rbtl-small-action {
    background: #234a3e;
    color: #f6f4ef;
}

.rbtl-footer-editor button:disabled {
    cursor: not-allowed;
    opacity: 0.72;
}

.rbtl-footer-section {
    padding: 24px;
}

.rbtl-footer-section header {
    margin-bottom: 20px;
}

.rbtl-form-grid {
    display: grid;
    gap: 16px;
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.rbtl-form-grid label,
.rbtl-footer-row label {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.rbtl-form-grid .wide {
    grid-column: 1 / -1;
}

.rbtl-footer-section input,
.rbtl-footer-section textarea {
    background: #fbfaf7;
    border: 1px solid rgba(28, 27, 25, 0.14);
    border-radius: 8px;
    color: #1c1b19;
    font: inherit;
    padding: 12px 14px;
}

.rbtl-footer-section textarea {
    resize: vertical;
}

.rbtl-footer-section input:focus,
.rbtl-footer-section textarea:focus {
    border-color: #234a3e;
    box-shadow: 0 0 0 3px rgba(35, 74, 62, 0.12);
    outline: none;
}

.rbtl-footer-section small {
    color: #b24a3e;
    font-size: 12px;
    font-weight: 700;
}

.rbtl-footer-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
    margin-top: 16px;
}

.rbtl-footer-row {
    align-items: end;
    border: 1px solid rgba(28, 27, 25, 0.08);
    border-radius: 8px;
    display: grid;
    gap: 12px;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) auto;
    padding: 14px;
}

.rbtl-footer-row.service {
    grid-template-columns: minmax(0, 1fr) auto;
}

.rbtl-footer-row button {
    background: #b24a3e;
    color: #ffffff;
}

.rbtl-small-action {
    margin-top: 16px;
}

.rbtl-footer-bottom-actions {
    justify-content: flex-end;
}

@media (max-width: 840px) {
    .rbtl-footer-actions,
    .rbtl-footer-bottom-actions {
        align-items: flex-start;
        flex-direction: column;
        gap: 14px;
    }

    .rbtl-form-grid,
    .rbtl-footer-row,
    .rbtl-footer-row.service {
        grid-template-columns: 1fr;
    }
}
</style>
