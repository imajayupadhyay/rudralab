<script setup>
import { computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    certificate: {
        type: Object,
        required: true,
    },
    mode: {
        type: String,
        required: true,
    },
    duplicateSource: {
        type: Object,
        default: null,
    },
});

const isEdit = computed(() => props.mode === 'edit');
const isDuplicate = computed(() => props.mode === 'duplicate');
const formModeLabel = computed(() => {
    if (isEdit.value) {
        return 'Update';
    }

    return isDuplicate.value ? 'Duplicate' : 'Create';
});
const formTitle = computed(() => {
    if (isEdit.value) {
        return props.certificate.certificate_number;
    }

    return isDuplicate.value ? 'Duplicate certificate record' : 'New certificate record';
});

const cardTypeOptions = [
    { value: 'type_1', label: 'Type 1', description: 'Existing RBTL identification card' },
    { value: 'type_2', label: 'Type 2', description: 'Rudraksha laboratory card' },
    { value: 'both', label: 'Both', description: 'Generate both card designs' },
];

const form = useForm({
    card_type: props.certificate.card_type || 'type_1',
    certificate_number: props.certificate.certificate_number || '',
    issued_at: props.certificate.issued_at || '',
    customer_name: props.certificate.customer_name || '',
    weight: props.certificate.weight || '',
    shape_cut: props.certificate.shape_cut || '',
    dimension: props.certificate.dimension || '',
    colour: props.certificate.colour || '',
    refractive_index: props.certificate.refractive_index || 'N/A',
    specific_gravity: props.certificate.specific_gravity || 'N/A',
    origin: props.certificate.origin || '',
    remarks: props.certificate.remarks || '',
    reference_code: props.certificate.reference_code || '',
    issue_location: props.certificate.issue_location || '',
    particulars: props.certificate.particulars || '',
    natural_faces: props.certificate.natural_faces || '',
    artificial_faces: props.certificate.artificial_faces || '',
    test_carried_out: props.certificate.test_carried_out || '',
    xray_results: props.certificate.xray_results || '',
    conclusions: props.certificate.conclusions || '',
    genus_type: props.certificate.genus_type || '',
    certificate_title: props.certificate.certificate_title || '',
    image_path: props.certificate.image_path || '',
    image: null,
    is_active: Boolean(props.certificate.is_active),
});

const showTypeOne = computed(() => ['type_1', 'both'].includes(form.card_type));
const showTypeTwo = computed(() => ['type_2', 'both'].includes(form.card_type));

const submit = () => {
    if (isEdit.value) {
        form
            .transform((data) => ({
                ...data,
                _method: 'put',
            }))
            .post(`/rbtl/certificates/${props.certificate.id}`, {
                forceFormData: true,
            });
        return;
    }

    form.post('/rbtl/certificates', {
        forceFormData: true,
    });
};

const setImage = (event) => {
    form.image = event.target.files[0] || null;
};
</script>

<template>
    <form class="rbtl-admin-card rbtl-cert-form" @submit.prevent="submit">
        <div class="rbtl-cert-form-head">
            <div>
                <span>{{ formModeLabel }}</span>
                <h2>{{ formTitle }}</h2>
                <p v-if="isDuplicate && duplicateSource">
                    Copied from {{ duplicateSource.certificate_number }}. New number: {{ form.certificate_number }}.
                </p>
            </div>
            <div class="rbtl-cert-form-actions">
                <Link href="/rbtl/certificates">Cancel</Link>
                <Link v-if="isEdit" :href="`/rbtl/certificates/${certificate.id}/duplicate`">Duplicate</Link>
                <button type="submit" :disabled="form.processing">
                    {{ form.processing ? 'Saving...' : 'Save Certificate' }}
                </button>
            </div>
        </div>

        <fieldset class="rbtl-card-type-picker">
            <legend>Certificate Card Design</legend>
            <div class="rbtl-card-type-options">
                <label v-for="option in cardTypeOptions" :key="option.value" :class="{ active: form.card_type === option.value }">
                    <input v-model="form.card_type" type="radio" name="card_type" :value="option.value" />
                    <span>
                        <strong>{{ option.label }}</strong>
                        <small>{{ option.description }}</small>
                    </span>
                </label>
            </div>
            <small v-if="form.errors.card_type" class="rbtl-cert-error">{{ form.errors.card_type }}</small>
        </fieldset>

        <section class="rbtl-cert-section">
            <header>
                <span>Shared Information</span>
                <h3>Certificate and specimen details</h3>
                <p>These values are used by every selected card design.</p>
            </header>

            <div class="rbtl-cert-form-grid">
                <label>
                    <span>Certificate Number</span>
                    <input v-model="form.certificate_number" type="text" placeholder="VGTL/GEM/211554" />
                    <small v-if="form.errors.certificate_number">{{ form.errors.certificate_number }}</small>
                </label>

                <label>
                    <span>Issue Date</span>
                    <input v-model="form.issued_at" type="date" />
                    <small v-if="form.errors.issued_at">{{ form.errors.issued_at }}</small>
                </label>

                <label>
                    <span>Weight</span>
                    <input v-model="form.weight" type="text" placeholder="25-30 GMS" />
                    <small v-if="form.errors.weight">{{ form.errors.weight }}</small>
                </label>

                <label>
                    <span>Shape/Cut</span>
                    <input v-model="form.shape_cut" type="text" placeholder="ROUND BEAD" />
                    <small v-if="form.errors.shape_cut">{{ form.errors.shape_cut }}</small>
                </label>

                <label>
                    <span>Dimension</span>
                    <input v-model="form.dimension" type="text" placeholder="8MM mm" />
                    <small v-if="form.errors.dimension">{{ form.errors.dimension }}</small>
                </label>

                <label>
                    <span>Colour</span>
                    <input v-model="form.colour" type="text" placeholder="BLACK" />
                    <small v-if="form.errors.colour">{{ form.errors.colour }}</small>
                </label>

                <label>
                    <span>Origin</span>
                    <input v-model="form.origin" type="text" placeholder="INDONESIA (JAVA)" />
                    <small v-if="form.errors.origin">{{ form.errors.origin }}</small>
                </label>

                <label class="rbtl-cert-wide">
                    <span>Existing Image Path</span>
                    <input v-model="form.image_path" type="text" placeholder="/images/rbtl/service-mukhi.png" />
                    <small v-if="form.errors.image_path">{{ form.errors.image_path }}</small>
                </label>

                <label>
                    <span>Upload Image</span>
                    <input type="file" accept="image/*" @change="setImage" />
                    <small v-if="form.errors.image">{{ form.errors.image }}</small>
                </label>
            </div>
        </section>

        <section v-if="showTypeOne" class="rbtl-cert-section">
            <header>
                <span>Type 1 Fields</span>
                <h3>Existing RBTL identification card</h3>
            </header>

            <div class="rbtl-cert-form-grid">
                <label>
                    <span>Issued To</span>
                    <input v-model="form.customer_name" type="text" placeholder="Customer name" />
                    <small v-if="form.errors.customer_name">{{ form.errors.customer_name }}</small>
                </label>

                <label>
                    <span>Refractive Index</span>
                    <input v-model="form.refractive_index" type="text" />
                    <small v-if="form.errors.refractive_index">{{ form.errors.refractive_index }}</small>
                </label>

                <label>
                    <span>Specific Gravity</span>
                    <input v-model="form.specific_gravity" type="text" />
                    <small v-if="form.errors.specific_gravity">{{ form.errors.specific_gravity }}</small>
                </label>

                <label class="rbtl-cert-wide">
                    <span>Remarks</span>
                    <textarea v-model="form.remarks" rows="4" placeholder="KARUNGALI BRACELET"></textarea>
                    <small v-if="form.errors.remarks">{{ form.errors.remarks }}</small>
                </label>
            </div>
        </section>

        <section v-if="showTypeTwo" class="rbtl-cert-section">
            <header>
                <span>Type 2 Fields</span>
                <h3>Rudraksha laboratory card</h3>
                <p>These values fill the approved fixed-layout Type 2 design.</p>
            </header>

            <div class="rbtl-cert-form-grid">
                <label>
                    <span>Reference Code</span>
                    <input v-model="form.reference_code" type="text" placeholder="RBTL/14" />
                    <small v-if="form.errors.reference_code">{{ form.errors.reference_code }}</small>
                </label>

                <label>
                    <span>Issue Location</span>
                    <input v-model="form.issue_location" type="text" placeholder="NEW DELHI" />
                    <small v-if="form.errors.issue_location">{{ form.errors.issue_location }}</small>
                </label>

                <label class="rbtl-cert-wide">
                    <span>Card Heading</span>
                    <input v-model="form.certificate_title" type="text" placeholder="NATURAL 13-MUKHI RUDRAKSHA" />
                    <small v-if="form.errors.certificate_title">{{ form.errors.certificate_title }}</small>
                </label>

                <label>
                    <span>Particulars</span>
                    <input v-model="form.particulars" type="text" placeholder="One loose bead" />
                    <small v-if="form.errors.particulars">{{ form.errors.particulars }}</small>
                </label>

                <label>
                    <span>Natural Faces</span>
                    <input v-model="form.natural_faces" type="text" placeholder="Thirteen" />
                    <small v-if="form.errors.natural_faces">{{ form.errors.natural_faces }}</small>
                </label>

                <label>
                    <span>Artificial Faces</span>
                    <input v-model="form.artificial_faces" type="text" placeholder="None" />
                    <small v-if="form.errors.artificial_faces">{{ form.errors.artificial_faces }}</small>
                </label>

                <label>
                    <span>Test Carried Out</span>
                    <input v-model="form.test_carried_out" type="text" placeholder="X-Rays, Magnification" />
                    <small v-if="form.errors.test_carried_out">{{ form.errors.test_carried_out }}</small>
                </label>

                <label class="rbtl-cert-wide">
                    <span>X-Ray Results</span>
                    <textarea v-model="form.xray_results" rows="3" placeholder="X-Ray shows 13 natural compartments"></textarea>
                    <small v-if="form.errors.xray_results">{{ form.errors.xray_results }}</small>
                </label>

                <label class="rbtl-cert-wide">
                    <span>Conclusions</span>
                    <textarea v-model="form.conclusions" rows="3" placeholder="Results confirm natural origin"></textarea>
                    <small v-if="form.errors.conclusions">{{ form.errors.conclusions }}</small>
                </label>

                <label class="rbtl-cert-wide">
                    <span>Genus / Type</span>
                    <input v-model="form.genus_type" type="text" placeholder="ELAEOCARPUS / E. GANITRUS" />
                    <small v-if="form.errors.genus_type">{{ form.errors.genus_type }}</small>
                </label>
            </div>
        </section>

        <label class="rbtl-cert-check rbtl-cert-active-check">
            <input v-model="form.is_active" type="checkbox" />
            <span>Active and searchable on public verification page</span>
        </label>

        <div v-if="certificate.image_url" class="rbtl-cert-preview">
            <span>Current image preview</span>
            <img :src="certificate.image_url" alt="Certificate item preview" />
        </div>
    </form>
</template>

<style>
.rbtl-cert-form {
    padding: 24px;
}

.rbtl-cert-form-head {
    align-items: center;
    display: flex;
    gap: 18px;
    justify-content: space-between;
    margin-bottom: 24px;
}

.rbtl-cert-form-head span,
.rbtl-cert-preview span {
    color: #6b6862;
    display: block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
}

.rbtl-cert-form-head h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 32px;
    line-height: 1;
    margin: 6px 0 0;
}

.rbtl-cert-form-head p {
    color: #6b6862;
    font-size: 13px;
    margin: 8px 0 0;
}

.rbtl-cert-form-actions {
    align-items: center;
    display: flex;
    gap: 12px;
}

.rbtl-cert-form-actions a {
    color: #234a3e;
    font-size: 13px;
    font-weight: 800;
    text-decoration: none;
}

.rbtl-cert-form-actions button {
    background: #234a3e;
    border: none;
    border-radius: 8px;
    color: #f6f4ef;
    cursor: pointer;
    font: inherit;
    font-size: 13px;
    font-weight: 800;
    padding: 12px 16px;
}

.rbtl-cert-form-actions button:disabled {
    cursor: not-allowed;
    opacity: 0.72;
}

.rbtl-card-type-picker {
    border: 1px solid rgba(35, 74, 62, 0.16);
    border-radius: 8px;
    margin: 0 0 22px;
    padding: 18px;
}

.rbtl-card-type-picker legend,
.rbtl-cert-section header > span {
    color: #234a3e;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.14em;
    padding: 0 6px;
    text-transform: uppercase;
}

.rbtl-card-type-options {
    display: grid;
    gap: 12px;
    grid-template-columns: repeat(3, minmax(0, 1fr));
}

.rbtl-card-type-options label {
    align-items: center;
    background: #fbfaf7;
    border: 1px solid rgba(28, 27, 25, 0.12);
    border-radius: 8px;
    cursor: pointer;
    flex-direction: row;
    gap: 11px;
    padding: 14px;
}

.rbtl-card-type-options label.active {
    background: rgba(35, 74, 62, 0.08);
    border-color: #234a3e;
    box-shadow: 0 0 0 3px rgba(35, 74, 62, 0.08);
}

.rbtl-card-type-options label > span {
    display: flex;
    flex-direction: column;
    gap: 3px;
    letter-spacing: 0;
    text-transform: none;
}

.rbtl-card-type-options strong {
    color: #1c1b19;
    font-size: 14px;
}

.rbtl-card-type-options small {
    color: #6b6862;
    font-size: 11.5px;
    line-height: 1.35;
}

.rbtl-cert-error {
    display: block;
    margin-top: 10px;
}

.rbtl-cert-section {
    border-top: 1px solid rgba(28, 27, 25, 0.09);
    padding: 24px 0;
}

.rbtl-cert-section header {
    margin-bottom: 18px;
}

.rbtl-cert-section header > span {
    display: block;
    padding: 0;
}

.rbtl-cert-section h3 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 28px;
    line-height: 1;
    margin: 6px 0 0;
}

.rbtl-cert-section header p {
    color: #6b6862;
    font-size: 13px;
    margin: 8px 0 0;
}

.rbtl-cert-form-grid {
    display: grid;
    gap: 18px;
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.rbtl-cert-form label {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.rbtl-cert-form label > span {
    color: #6b6862;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.12em;
    text-transform: uppercase;
}

.rbtl-cert-form input[type='text'],
.rbtl-cert-form input[type='date'],
.rbtl-cert-form input[type='file'],
.rbtl-cert-form textarea {
    background: #fbfaf7;
    border: 1px solid rgba(28, 27, 25, 0.14);
    border-radius: 8px;
    color: #1c1b19;
    font: inherit;
    padding: 12px 14px;
}

.rbtl-cert-form textarea {
    resize: vertical;
}

.rbtl-cert-form input:focus,
.rbtl-cert-form textarea:focus {
    border-color: #234a3e;
    box-shadow: 0 0 0 3px rgba(35, 74, 62, 0.12);
    outline: none;
}

.rbtl-cert-form small {
    color: #b24a3e;
    font-size: 12.5px;
}

.rbtl-cert-wide {
    grid-column: 1 / -1;
}

.rbtl-cert-check {
    align-items: center;
    flex-direction: row !important;
    gap: 10px !important;
    grid-column: 1 / -1;
}

.rbtl-cert-check span {
    color: #1c1b19 !important;
    font-size: 14px !important;
    letter-spacing: 0 !important;
    text-transform: none !important;
}

.rbtl-cert-active-check {
    border-top: 1px solid rgba(28, 27, 25, 0.09);
    margin-top: 2px;
    padding-top: 22px;
}

.rbtl-cert-preview {
    border-top: 1px solid rgba(28, 27, 25, 0.08);
    margin-top: 24px;
    padding-top: 22px;
}

.rbtl-cert-preview img {
    border: 1px solid rgba(28, 27, 25, 0.1);
    border-radius: 8px;
    display: block;
    height: 180px;
    margin-top: 12px;
    object-fit: cover;
    width: 260px;
}

@media (max-width: 760px) {
    .rbtl-cert-form-head,
    .rbtl-cert-form-actions {
        align-items: flex-start;
        flex-direction: column;
    }

    .rbtl-cert-form-grid {
        grid-template-columns: 1fr;
    }

    .rbtl-card-type-options {
        grid-template-columns: 1fr;
    }

    .rbtl-cert-preview img {
        width: 100%;
    }
}
</style>
