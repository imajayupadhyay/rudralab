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
});

const isEdit = computed(() => props.mode === 'edit');

const form = useForm({
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
    image_path: props.certificate.image_path || '',
    image: null,
    is_active: Boolean(props.certificate.is_active),
});

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
                <span>{{ isEdit ? 'Update' : 'Create' }}</span>
                <h2>{{ isEdit ? certificate.certificate_number : 'New certificate record' }}</h2>
            </div>
            <div class="rbtl-cert-form-actions">
                <Link href="/rbtl/certificates">Cancel</Link>
                <button type="submit" :disabled="form.processing">
                    {{ form.processing ? 'Saving...' : 'Save Certificate' }}
                </button>
            </div>
        </div>

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
                <span>Issued To</span>
                <input v-model="form.customer_name" type="text" placeholder="Customer name" />
                <small v-if="form.errors.customer_name">{{ form.errors.customer_name }}</small>
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
                <span>Existing Image Path</span>
                <input v-model="form.image_path" type="text" placeholder="/images/rbtl/service-mukhi.png" />
                <small v-if="form.errors.image_path">{{ form.errors.image_path }}</small>
            </label>

            <label>
                <span>Upload Image</span>
                <input type="file" accept="image/*" @change="setImage" />
                <small v-if="form.errors.image">{{ form.errors.image }}</small>
            </label>

            <label class="rbtl-cert-wide">
                <span>Remarks</span>
                <textarea v-model="form.remarks" rows="4" placeholder="KARUNGALI BRACELET"></textarea>
                <small v-if="form.errors.remarks">{{ form.errors.remarks }}</small>
            </label>

            <label class="rbtl-cert-check">
                <input v-model="form.is_active" type="checkbox" />
                <span>Active and searchable on public verification page</span>
            </label>
        </div>

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

    .rbtl-cert-preview img {
        width: 100%;
    }
}
</style>
