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
    hero_egac_image: null,
    hero_iso_image: null,
    vision_image: null,
    service_0_image: null,
    service_1_image: null,
    service_2_image: null,
    service_3_image: null,
    service_4_image: null,
    service_5_image: null,
});

const setFile = (key, event) => {
    form[key] = event.target.files[0] || null;
};

const submit = () => {
    form
        .transform((data) => ({
            ...data,
            _method: 'put',
        }))
        .post('/rbtl/homepage', {
            forceFormData: true,
            preserveScroll: true,
        });
};

const addStat = () => form.content.stats.push({ value: '', label: '' });
const removeStat = (index) => form.content.stats.splice(index, 1);

const addParagraph = () => form.content.welcome.paragraphs.push('');
const removeParagraph = (index) => form.content.welcome.paragraphs.splice(index, 1);

const addTestingItem = () => form.content.testing.items.push('');
const removeTestingItem = (index) => form.content.testing.items.splice(index, 1);

const addMissionItem = () => form.content.vision_mission.mission.push('');
const removeMissionItem = (index) => form.content.vision_mission.mission.splice(index, 1);

const addVisionItem = () => form.content.vision_mission.vision.push('');
const removeVisionItem = (index) => form.content.vision_mission.vision.splice(index, 1);

const addService = () => {
    form.content.services.items.push({
        title: '',
        desc: '',
        img: '',
        link_text: 'Learn more',
        link_url: '/contact',
    });
};

const removeService = (index) => form.content.services.items.splice(index, 1);
</script>

<template>
    <Head title="Manage Homepage" />

    <AdminLayout title="Homepage" eyebrow="Content Management">
        <form class="rbtl-home-editor" @submit.prevent="submit">
            <div class="rbtl-home-editor-actions">
                <div>
                    <span>Public Page</span>
                    <h2>Homepage sections</h2>
                </div>
                <button type="submit" :disabled="form.processing">
                    {{ form.processing ? 'Saving...' : 'Save Homepage' }}
                </button>
            </div>

            <section class="rbtl-admin-card rbtl-home-section">
                <header>
                    <span>Section 01</span>
                    <h3>Hero</h3>
                </header>

                <div class="rbtl-form-grid">
                    <label>
                        <span>Badge Text</span>
                        <input v-model="form.content.hero.badge" type="text" />
                    </label>
                    <label>
                        <span>Accreditation Label</span>
                        <input v-model="form.content.hero.accreditation_label" type="text" />
                    </label>
                    <label>
                        <span>Main Heading</span>
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
                    <label>
                        <span>Primary Button Text</span>
                        <input v-model="form.content.hero.primary_button_text" type="text" />
                    </label>
                    <label>
                        <span>Primary Button URL</span>
                        <input v-model="form.content.hero.primary_button_url" type="text" />
                    </label>
                    <label>
                        <span>Secondary Button Text</span>
                        <input v-model="form.content.hero.secondary_button_text" type="text" />
                    </label>
                    <label>
                        <span>Secondary Button URL</span>
                        <input v-model="form.content.hero.secondary_button_url" type="text" />
                    </label>
                    <label>
                        <span>EGAC Image Path</span>
                        <input v-model="form.content.hero.egac_image" type="text" />
                    </label>
                    <label>
                        <span>Upload EGAC Image</span>
                        <input type="file" accept="image/*" @change="setFile('hero_egac_image', $event)" />
                    </label>
                    <label>
                        <span>EGAC Alt Text</span>
                        <input v-model="form.content.hero.egac_alt" type="text" />
                    </label>
                    <label>
                        <span>ISO Image Path</span>
                        <input v-model="form.content.hero.iso_image" type="text" />
                    </label>
                    <label>
                        <span>Upload ISO Image</span>
                        <input type="file" accept="image/*" @change="setFile('hero_iso_image', $event)" />
                    </label>
                    <label>
                        <span>ISO Alt Text</span>
                        <input v-model="form.content.hero.iso_alt" type="text" />
                    </label>
                </div>
            </section>

            <section class="rbtl-admin-card rbtl-home-section">
                <header>
                    <span>Section 02</span>
                    <h3>Stats Strip</h3>
                </header>

                <div class="rbtl-repeat-list">
                    <div v-for="(stat, index) in form.content.stats" :key="index" class="rbtl-repeat-row two">
                        <label>
                            <span>Value</span>
                            <input v-model="stat.value" type="text" />
                        </label>
                        <label>
                            <span>Label</span>
                            <input v-model="stat.label" type="text" />
                        </label>
                        <button type="button" @click="removeStat(index)">Remove</button>
                    </div>
                </div>
                <button class="rbtl-small-action" type="button" @click="addStat">Add Stat</button>
            </section>

            <section class="rbtl-admin-card rbtl-home-section">
                <header>
                    <span>Section 03</span>
                    <h3>Welcome</h3>
                </header>

                <div class="rbtl-form-grid">
                    <label>
                        <span>Eyebrow</span>
                        <input v-model="form.content.welcome.eyebrow" type="text" />
                    </label>
                    <label>
                        <span>Heading</span>
                        <input v-model="form.content.welcome.title" type="text" />
                    </label>
                </div>

                <div class="rbtl-repeat-list">
                    <div v-for="(paragraph, index) in form.content.welcome.paragraphs" :key="index" class="rbtl-repeat-row text">
                        <label>
                            <span>Paragraph {{ index + 1 }}</span>
                            <textarea v-model="form.content.welcome.paragraphs[index]" rows="3"></textarea>
                        </label>
                        <button type="button" @click="removeParagraph(index)">Remove</button>
                    </div>
                </div>
                <button class="rbtl-small-action" type="button" @click="addParagraph">Add Paragraph</button>
            </section>

            <section class="rbtl-admin-card rbtl-home-section">
                <header>
                    <span>Section 04</span>
                    <h3>What We Test</h3>
                </header>

                <div class="rbtl-form-grid">
                    <label>
                        <span>Heading</span>
                        <input v-model="form.content.testing.title" type="text" />
                    </label>
                    <label>
                        <span>Subtitle</span>
                        <input v-model="form.content.testing.subtitle" type="text" />
                    </label>
                </div>

                <div class="rbtl-repeat-list">
                    <div v-for="(item, index) in form.content.testing.items" :key="index" class="rbtl-repeat-row text">
                        <label>
                            <span>Checklist Item {{ index + 1 }}</span>
                            <input v-model="form.content.testing.items[index]" type="text" />
                        </label>
                        <button type="button" @click="removeTestingItem(index)">Remove</button>
                    </div>
                </div>
                <button class="rbtl-small-action" type="button" @click="addTestingItem">Add Item</button>
            </section>

            <section class="rbtl-admin-card rbtl-home-section">
                <header>
                    <span>Section 05</span>
                    <h3>Vision & Mission</h3>
                </header>

                <div class="rbtl-form-grid">
                    <label>
                        <span>Eyebrow</span>
                        <input v-model="form.content.vision_mission.eyebrow" type="text" />
                    </label>
                    <label>
                        <span>Heading</span>
                        <input v-model="form.content.vision_mission.title" type="text" />
                    </label>
                    <label class="wide">
                        <span>Description</span>
                        <textarea v-model="form.content.vision_mission.description" rows="4"></textarea>
                    </label>
                    <label>
                        <span>Image Path</span>
                        <input v-model="form.content.vision_mission.image" type="text" />
                    </label>
                    <label>
                        <span>Upload Image</span>
                        <input type="file" accept="image/*" @change="setFile('vision_image', $event)" />
                    </label>
                    <label>
                        <span>Image Alt Text</span>
                        <input v-model="form.content.vision_mission.image_alt" type="text" />
                    </label>
                    <label>
                        <span>Mission Heading</span>
                        <input v-model="form.content.vision_mission.mission_title" type="text" />
                    </label>
                    <label>
                        <span>Vision Heading</span>
                        <input v-model="form.content.vision_mission.vision_title" type="text" />
                    </label>
                </div>

                <div class="rbtl-two-editors">
                    <div>
                        <h4>Mission Items</h4>
                        <div class="rbtl-repeat-list">
                            <div v-for="(item, index) in form.content.vision_mission.mission" :key="index" class="rbtl-repeat-row text">
                                <label>
                                    <span>Mission {{ index + 1 }}</span>
                                    <input v-model="form.content.vision_mission.mission[index]" type="text" />
                                </label>
                                <button type="button" @click="removeMissionItem(index)">Remove</button>
                            </div>
                        </div>
                        <button class="rbtl-small-action" type="button" @click="addMissionItem">Add Mission</button>
                    </div>

                    <div>
                        <h4>Vision Items</h4>
                        <div class="rbtl-repeat-list">
                            <div v-for="(item, index) in form.content.vision_mission.vision" :key="index" class="rbtl-repeat-row text">
                                <label>
                                    <span>Vision {{ index + 1 }}</span>
                                    <input v-model="form.content.vision_mission.vision[index]" type="text" />
                                </label>
                                <button type="button" @click="removeVisionItem(index)">Remove</button>
                            </div>
                        </div>
                        <button class="rbtl-small-action" type="button" @click="addVisionItem">Add Vision</button>
                    </div>
                </div>
            </section>

            <section class="rbtl-admin-card rbtl-home-section">
                <header>
                    <span>Section 06</span>
                    <h3>Services</h3>
                </header>

                <div class="rbtl-form-grid">
                    <label>
                        <span>Eyebrow</span>
                        <input v-model="form.content.services.eyebrow" type="text" />
                    </label>
                    <label>
                        <span>Heading</span>
                        <input v-model="form.content.services.title" type="text" />
                    </label>
                </div>

                <div class="rbtl-service-list">
                    <div v-for="(service, index) in form.content.services.items" :key="index" class="rbtl-service-editor">
                        <div class="rbtl-service-head">
                            <h4>Service {{ index + 1 }}</h4>
                            <button type="button" @click="removeService(index)">Remove</button>
                        </div>
                        <div class="rbtl-form-grid">
                            <label>
                                <span>Title</span>
                                <input v-model="service.title" type="text" />
                            </label>
                            <label>
                                <span>Image Path</span>
                                <input v-model="service.img" type="text" />
                            </label>
                            <label>
                                <span>Upload Image</span>
                                <input
                                    type="file"
                                    accept="image/*"
                                    @change="setFile(`service_${index}_image`, $event)"
                                />
                            </label>
                            <label>
                                <span>Link Text</span>
                                <input v-model="service.link_text" type="text" />
                            </label>
                            <label>
                                <span>Link URL</span>
                                <input v-model="service.link_url" type="text" />
                            </label>
                            <label class="wide">
                                <span>Description</span>
                                <textarea v-model="service.desc" rows="3"></textarea>
                            </label>
                        </div>
                    </div>
                </div>
                <button class="rbtl-small-action" type="button" @click="addService">Add Service</button>
            </section>

            <section class="rbtl-admin-card rbtl-home-section">
                <header>
                    <span>Section 07</span>
                    <h3>Call To Action</h3>
                </header>

                <div class="rbtl-form-grid">
                    <label>
                        <span>Heading</span>
                        <input v-model="form.content.cta.title" type="text" />
                    </label>
                    <label>
                        <span>Description</span>
                        <input v-model="form.content.cta.description" type="text" />
                    </label>
                    <label>
                        <span>Primary Button Text</span>
                        <input v-model="form.content.cta.primary_button_text" type="text" />
                    </label>
                    <label>
                        <span>Primary Button URL</span>
                        <input v-model="form.content.cta.primary_button_url" type="text" />
                    </label>
                    <label>
                        <span>Secondary Button Text</span>
                        <input v-model="form.content.cta.secondary_button_text" type="text" />
                    </label>
                    <label>
                        <span>Secondary Button URL</span>
                        <input v-model="form.content.cta.secondary_button_url" type="text" />
                    </label>
                </div>
            </section>

            <div class="rbtl-home-bottom-actions">
                <button type="submit" :disabled="form.processing">
                    {{ form.processing ? 'Saving...' : 'Save Homepage' }}
                </button>
            </div>
        </form>
    </AdminLayout>
</template>

<style>
.rbtl-home-editor {
    display: flex;
    flex-direction: column;
    gap: 22px;
}

.rbtl-home-editor-actions,
.rbtl-home-bottom-actions {
    align-items: center;
    display: flex;
    justify-content: space-between;
}

.rbtl-home-editor-actions span,
.rbtl-home-section header span,
.rbtl-home-section label > span {
    color: #6b6862;
    display: block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
}

.rbtl-home-editor-actions h2,
.rbtl-home-section h3 {
    font-family: 'Cormorant Garamond', serif;
    line-height: 1;
    margin: 6px 0 0;
}

.rbtl-home-editor-actions h2 {
    font-size: 34px;
}

.rbtl-home-section h3 {
    font-size: 30px;
}

.rbtl-home-editor button[type='submit'],
.rbtl-small-action,
.rbtl-repeat-row button,
.rbtl-service-head button {
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font: inherit;
    font-size: 13px;
    font-weight: 800;
    padding: 11px 15px;
}

.rbtl-home-editor button[type='submit'],
.rbtl-small-action {
    background: #234a3e;
    color: #f6f4ef;
}

.rbtl-home-editor button:disabled {
    cursor: not-allowed;
    opacity: 0.72;
}

.rbtl-home-section {
    padding: 24px;
}

.rbtl-home-section header {
    margin-bottom: 20px;
}

.rbtl-form-grid {
    display: grid;
    gap: 16px;
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.rbtl-form-grid label,
.rbtl-repeat-row label {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.rbtl-form-grid .wide {
    grid-column: 1 / -1;
}

.rbtl-home-section input[type='text'],
.rbtl-home-section input[type='file'],
.rbtl-home-section textarea {
    background: #fbfaf7;
    border: 1px solid rgba(28, 27, 25, 0.14);
    border-radius: 8px;
    color: #1c1b19;
    font: inherit;
    padding: 12px 14px;
}

.rbtl-home-section textarea {
    resize: vertical;
}

.rbtl-home-section input:focus,
.rbtl-home-section textarea:focus {
    border-color: #234a3e;
    box-shadow: 0 0 0 3px rgba(35, 74, 62, 0.12);
    outline: none;
}

.rbtl-repeat-list,
.rbtl-service-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
    margin-top: 18px;
}

.rbtl-repeat-row {
    align-items: end;
    border: 1px solid rgba(28, 27, 25, 0.08);
    border-radius: 8px;
    display: grid;
    gap: 12px;
    padding: 14px;
}

.rbtl-repeat-row.two {
    grid-template-columns: minmax(120px, 0.35fr) minmax(0, 1fr) auto;
}

.rbtl-repeat-row.text {
    grid-template-columns: minmax(0, 1fr) auto;
}

.rbtl-repeat-row button,
.rbtl-service-head button {
    background: #b24a3e;
    color: #ffffff;
}

.rbtl-two-editors {
    display: grid;
    gap: 22px;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    margin-top: 22px;
}

.rbtl-two-editors h4,
.rbtl-service-head h4 {
    font-size: 15px;
    margin: 0;
}

.rbtl-service-editor {
    border: 1px solid rgba(28, 27, 25, 0.08);
    border-radius: 8px;
    padding: 18px;
}

.rbtl-service-head {
    align-items: center;
    display: flex;
    justify-content: space-between;
    margin-bottom: 16px;
}

.rbtl-home-bottom-actions {
    justify-content: flex-end;
}

@media (max-width: 840px) {
    .rbtl-home-editor-actions,
    .rbtl-home-bottom-actions,
    .rbtl-service-head {
        align-items: flex-start;
        flex-direction: column;
        gap: 14px;
    }

    .rbtl-form-grid,
    .rbtl-two-editors,
    .rbtl-repeat-row.two,
    .rbtl-repeat-row.text {
        grid-template-columns: 1fr;
    }
}
</style>
