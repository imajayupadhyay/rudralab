<script setup>
import { computed, nextTick, reactive, ref, watch } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    certificates: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({ search: '' }),
    },
});

const form = reactive({
    search: props.filters.search || '',
});
const page = usePage();
const selectedIds = ref([]);
const bulkType = ref('');
const bulkForm = ref(null);

const csrfToken = computed(() => page.props.csrf_token || '');
const visibleIds = computed(() => props.certificates.data.map((certificate) => certificate.id));
const selectedCount = computed(() => selectedIds.value.length);
const totalCount = computed(() => props.certificates.total ?? props.certificates.data.length);
const allVisibleSelected = computed(() => (
    visibleIds.value.length > 0
    && visibleIds.value.every((id) => selectedIds.value.includes(id))
));

const supportsDownload = (certificate, type) => (
    certificate.available_download_types || []
).includes(type);

const selectedAvailableCount = (type) => props.certificates.data.filter((certificate) => (
    selectedIds.value.includes(certificate.id) && supportsDownload(certificate, type)
)).length;

const certificateOneCount = computed(() => selectedAvailableCount('type_1'));
const certificateTwoCount = computed(() => selectedAvailableCount('type_2'));
const isSelected = (id) => selectedIds.value.includes(id);

const setSelected = (id, checked) => {
    if (checked && !selectedIds.value.includes(id)) {
        selectedIds.value = [...selectedIds.value, id];
        return;
    }

    if (!checked) {
        selectedIds.value = selectedIds.value.filter((selectedId) => selectedId !== id);
    }
};

const toggleAllVisible = (event) => {
    if (event.target.checked) {
        selectedIds.value = [...new Set([...selectedIds.value, ...visibleIds.value])];
        return;
    }

    selectedIds.value = selectedIds.value.filter((id) => !visibleIds.value.includes(id));
};

const clearSelection = () => {
    selectedIds.value = [];
};

const downloadUrl = (certificate, type) => `/rbtl/certificates/${certificate.id}/download/${type}`;

const submitBulkDownload = async (type) => {
    if (!selectedAvailableCount(type)) {
        return;
    }

    bulkType.value = type;
    await nextTick();
    bulkForm.value?.submit();
};

watch(
    () => visibleIds.value.join(','),
    () => {
        selectedIds.value = selectedIds.value.filter((id) => visibleIds.value.includes(id));
    },
);

const search = () => {
    router.get('/rbtl/certificates', { search: form.search }, {
        preserveState: true,
        replace: true,
    });
};

const resetSearch = () => {
    form.search = '';
    router.get('/rbtl/certificates', {}, { replace: true });
};

const cardTypeLabel = (type) => ({
    type_1: 'Type 1',
    type_2: 'Type 2',
    both: 'Both',
}[type] || 'Type 1');

const cardTypeClass = (type) => ({
    type_1: 'type-one',
    type_2: 'type-two',
    both: 'type-both',
}[type] || 'type-one');

const destroyCertificate = (certificate) => {
    if (!window.confirm(`Delete certificate ${certificate.certificate_number}?`)) {
        return;
    }

    router.delete(`/rbtl/certificates/${certificate.id}`);
};
</script>

<template>
    <Head title="RBTL Certificates" />

    <AdminLayout title="Certificates">
        <section class="rbtl-admin-card rbtl-cert-index">
            <div class="rbtl-cert-hero">
                <div class="rbtl-cert-title-block">
                    <span>Certificate Registry</span>
                    <h2>Certificate records</h2>
                </div>

                <div class="rbtl-cert-hero-side">
                    <div class="rbtl-cert-total">
                        <strong>{{ totalCount }}</strong>
                        <span>Total records</span>
                    </div>
                    <Link class="rbtl-cert-create" href="/rbtl/certificates/create">
                        <span>+</span>
                        Add Certificate
                    </Link>
                </div>
            </div>

            <form class="rbtl-cert-toolbar" @submit.prevent="search">
                <label class="rbtl-cert-search-field">
                    <span>Search records</span>
                    <input v-model="form.search" type="search" placeholder="Certificate, customer, origin, remarks" />
                </label>
                <button type="submit" class="rbtl-cert-search-button">Search</button>
                <button type="button" class="rbtl-cert-reset-button" @click="resetSearch">Reset</button>
            </form>

            <form ref="bulkForm" class="rbtl-download-form" method="post" action="/rbtl/certificates/download">
                <input type="hidden" name="_token" :value="csrfToken" />
                <input type="hidden" name="card_type" :value="bulkType" />
                <input
                    v-for="id in selectedIds"
                    :key="id"
                    type="hidden"
                    name="certificate_ids[]"
                    :value="id"
                />
            </form>

            <div v-if="selectedCount" class="rbtl-bulk-panel">
                <div class="rbtl-bulk-summary">
                    <strong>{{ selectedCount }} selected</strong>
                    <span>{{ certificateOneCount }} have Certificate 1 · {{ certificateTwoCount }} have Certificate 2</span>
                </div>
                <div class="rbtl-bulk-actions">
                    <button type="button" :disabled="!certificateOneCount" @click="submitBulkDownload('type_1')">
                        Download Cert 1
                    </button>
                    <button type="button" :disabled="!certificateTwoCount" @click="submitBulkDownload('type_2')">
                        Download Cert 2
                    </button>
                    <button type="button" class="secondary" @click="clearSelection">Clear</button>
                </div>
            </div>

            <div class="rbtl-cert-table-wrap">
                <table class="rbtl-cert-table">
                    <thead>
                        <tr>
                            <th class="rbtl-select-cell">
                                <input
                                    type="checkbox"
                                    aria-label="Select all certificates on this page"
                                    :checked="allVisibleSelected"
                                    @change="toggleAllVisible"
                                />
                            </th>
                            <th>Record</th>
                            <th>Details</th>
                            <th>Status</th>
                            <th>PDF</th>
                            <th class="rbtl-manage-head">Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="certificate in certificates.data"
                            :key="certificate.id"
                            :class="{ 'is-selected': isSelected(certificate.id) }"
                        >
                            <td class="rbtl-select-cell">
                                <input
                                    type="checkbox"
                                    :aria-label="`Select certificate ${certificate.certificate_number}`"
                                    :checked="isSelected(certificate.id)"
                                    @change="setSelected(certificate.id, $event.target.checked)"
                                />
                            </td>
                            <td class="rbtl-record-cell">
                                <strong>{{ certificate.certificate_number }}</strong>
                                <span>{{ certificate.customer_name || 'No customer assigned' }}</span>
                                <em :class="cardTypeClass(certificate.card_type)">
                                    {{ cardTypeLabel(certificate.card_type) }}
                                </em>
                            </td>
                            <td>
                                <div class="rbtl-detail-stack">
                                    <span>Issued: <strong>{{ certificate.issued_at || '-' }}</strong></span>
                                    <span>Origin: <strong>{{ certificate.origin || '-' }}</strong></span>
                                </div>
                            </td>
                            <td>
                                <span class="rbtl-status" :class="{ inactive: !certificate.is_active }">
                                    <i></i>
                                    {{ certificate.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="rbtl-download-chips">
                                    <a
                                        v-if="supportsDownload(certificate, 'type_1')"
                                        :href="downloadUrl(certificate, 'type_1')"
                                        aria-label="Download Certificate 1 PDF"
                                        title="Download Certificate 1 PDF"
                                    >Cert 1</a>
                                    <a
                                        v-if="supportsDownload(certificate, 'type_2')"
                                        :href="downloadUrl(certificate, 'type_2')"
                                        aria-label="Download Certificate 2 PDF"
                                        title="Download Certificate 2 PDF"
                                    >Cert 2</a>
                                    <span v-if="!(certificate.available_download_types || []).length">-</span>
                                </div>
                            </td>
                            <td class="rbtl-row-actions">
                                <details class="rbtl-actions-menu">
                                    <summary>Manage</summary>
                                    <div>
                                        <Link :href="`/rbtl/certificates/${certificate.id}/edit`">Edit record</Link>
                                        <Link :href="`/rbtl/certificates/${certificate.id}/duplicate`">Duplicate</Link>
                                        <button type="button" @click="destroyCertificate(certificate)">Delete</button>
                                    </div>
                                </details>
                            </td>
                        </tr>
                        <tr v-if="!certificates.data.length">
                            <td colspan="6" class="rbtl-empty-cell">No certificate records found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="certificates.links?.length > 3" class="rbtl-pagination">
                <Link
                    v-for="link in certificates.links"
                    :key="link.label"
                    :href="link.url || '#'"
                    :class="{ active: link.active, disabled: !link.url }"
                    v-html="link.label"
                />
            </div>
        </section>
    </AdminLayout>
</template>

<style>
.rbtl-cert-index {
    border-radius: 8px;
    overflow: hidden;
}

.rbtl-cert-hero {
    align-items: center;
    background: #fbfaf7;
    border-bottom: 1px solid rgba(28, 27, 25, 0.08);
    display: flex;
    gap: 18px;
    justify-content: space-between;
    padding: 24px;
}

.rbtl-cert-title-block span {
    color: #6b6862;
    display: block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0;
    text-transform: uppercase;
}

.rbtl-cert-title-block h2 {
    color: #1c1b19;
    font-family: 'Cormorant Garamond', serif;
    font-size: 34px;
    line-height: 1;
    margin: 7px 0 0;
}

.rbtl-cert-hero-side {
    align-items: center;
    display: flex;
    flex-shrink: 0;
    gap: 12px;
}

.rbtl-cert-total {
    align-items: flex-end;
    border-right: 1px solid rgba(28, 27, 25, 0.12);
    display: flex;
    flex-direction: column;
    padding-right: 14px;
}

.rbtl-cert-total strong {
    color: #1c1b19;
    font-size: 24px;
    line-height: 1;
}

.rbtl-cert-total span {
    color: #6b6862;
    font-size: 12px;
    font-weight: 800;
    margin-top: 4px;
}

.rbtl-cert-create {
    align-items: center;
    background: #234a3e;
    border-radius: 8px;
    color: #f6f4ef;
    display: inline-flex;
    font-size: 13px;
    font-weight: 800;
    gap: 8px;
    min-height: 40px;
    padding: 0 15px;
    text-decoration: none;
}

.rbtl-cert-create span {
    font-size: 18px;
    line-height: 1;
}

.rbtl-cert-toolbar {
    align-items: end;
    display: grid;
    gap: 10px;
    grid-template-columns: minmax(0, 1fr) auto auto;
    padding: 18px 24px;
}

.rbtl-cert-search-field {
    display: flex;
    flex-direction: column;
    gap: 7px;
    min-width: 0;
}

.rbtl-cert-search-field span {
    color: #6b6862;
    font-size: 12px;
    font-weight: 800;
}

.rbtl-cert-search-field input {
    background: #ffffff;
    border: 1px solid rgba(28, 27, 25, 0.14);
    border-radius: 8px;
    color: #1c1b19;
    font: inherit;
    min-height: 42px;
    padding: 0 13px;
}

.rbtl-cert-search-field input:focus {
    border-color: #234a3e;
    box-shadow: 0 0 0 3px rgba(35, 74, 62, 0.12);
    outline: none;
}

.rbtl-cert-search-button,
.rbtl-cert-reset-button,
.rbtl-bulk-actions button {
    border-radius: 8px;
    cursor: pointer;
    font: inherit;
    font-size: 13px;
    font-weight: 800;
    min-height: 42px;
    padding: 0 15px;
}

.rbtl-cert-search-button,
.rbtl-bulk-actions button {
    background: #234a3e;
    border: 1px solid #234a3e;
    color: #f6f4ef;
}

.rbtl-cert-reset-button,
.rbtl-bulk-actions button.secondary {
    background: #ffffff;
    border: 1px solid rgba(28, 27, 25, 0.14);
    color: #1c1b19;
}

.rbtl-download-form {
    display: none;
}

.rbtl-bulk-panel {
    align-items: center;
    background: #203f36;
    color: #f6f4ef;
    display: flex;
    gap: 16px;
    justify-content: space-between;
    margin: 0 24px 18px;
    padding: 13px 14px;
}

.rbtl-bulk-summary strong {
    display: block;
    font-size: 14px;
}

.rbtl-bulk-summary span {
    color: rgba(246, 244, 239, 0.72);
    display: block;
    font-size: 12px;
    font-weight: 700;
    margin-top: 3px;
}

.rbtl-bulk-actions {
    align-items: center;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    justify-content: flex-end;
}

.rbtl-bulk-actions button {
    min-height: 36px;
    padding: 0 12px;
}

.rbtl-bulk-actions button:not(.secondary) {
    background: #f6f4ef;
    border-color: #f6f4ef;
    color: #203f36;
}

.rbtl-bulk-actions button.secondary {
    background: transparent;
    border-color: rgba(246, 244, 239, 0.36);
    color: #f6f4ef;
}

.rbtl-bulk-actions button:disabled {
    cursor: not-allowed;
    opacity: 0.45;
}

.rbtl-cert-table-wrap {
    overflow-x: auto;
    padding: 0 24px 24px;
}

.rbtl-cert-table {
    border-collapse: separate;
    border-spacing: 0;
    min-width: 960px;
    width: 100%;
}

.rbtl-cert-table th {
    background: #f3f1eb;
    color: #6b6862;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0;
    padding: 12px 14px;
    text-align: left;
    text-transform: uppercase;
}

.rbtl-cert-table th:first-child {
    border-radius: 8px 0 0 8px;
}

.rbtl-cert-table th:last-child {
    border-radius: 0 8px 8px 0;
}

.rbtl-select-cell {
    text-align: center !important;
    width: 44px;
}

.rbtl-select-cell input {
    accent-color: #234a3e;
    cursor: pointer;
    height: 16px;
    width: 16px;
}

.rbtl-cert-table td {
    border-top: 1px solid rgba(28, 27, 25, 0.08);
    color: #4a4842;
    font-size: 14px;
    padding: 17px 14px;
    vertical-align: top;
}

.rbtl-cert-table tbody tr {
    transition: background 0.14s ease;
}

.rbtl-cert-table tbody tr:hover,
.rbtl-cert-table tbody tr.is-selected {
    background: #fbfaf7;
}

.rbtl-record-cell strong {
    color: #1c1b19;
    display: block;
    font-size: 15px;
}

.rbtl-record-cell > span {
    color: #6b6862;
    display: block;
    font-size: 12px;
    margin-top: 5px;
}

.rbtl-record-cell em,
.rbtl-status,
.rbtl-download-chips a {
    align-items: center;
    border-radius: 999px;
    display: inline-flex;
    font-size: 12px;
    font-weight: 800;
    font-style: normal;
    line-height: 1;
}

.rbtl-record-cell em {
    margin-top: 8px;
    padding: 6px 9px;
}

.rbtl-record-cell em.type-one {
    background: #edf4ef;
    color: #234a3e;
}

.rbtl-record-cell em.type-two {
    background: #eef2fb;
    color: #274a8b;
}

.rbtl-record-cell em.type-both {
    background: #f6eedf;
    color: #7a5421;
}

.rbtl-detail-stack {
    display: grid;
    gap: 7px;
}

.rbtl-detail-stack span {
    color: #6b6862;
    display: block;
    font-size: 13px;
}

.rbtl-detail-stack strong {
    color: #1c1b19;
}

.rbtl-status {
    background: #edf4ef;
    color: #234a3e;
    gap: 7px;
    padding: 7px 9px;
}

.rbtl-status i {
    background: #42a06b;
    border-radius: 50%;
    display: block;
    height: 7px;
    width: 7px;
}

.rbtl-status.inactive {
    background: #fbefec;
    color: #a34032;
}

.rbtl-status.inactive i {
    background: #b24a3e;
}

.rbtl-download-chips {
    align-items: center;
    display: flex;
    gap: 7px;
    min-height: 30px;
}

.rbtl-download-chips a {
    background: #ffffff;
    border: 1px solid rgba(35, 74, 62, 0.2);
    color: #234a3e;
    justify-content: center;
    min-width: 40px;
    padding: 8px 10px;
    text-decoration: none;
}

.rbtl-download-chips a:hover {
    background: #234a3e;
    color: #f6f4ef;
}

.rbtl-download-chips span {
    color: #9a968d;
    font-weight: 800;
}

.rbtl-manage-head,
.rbtl-row-actions {
    text-align: right !important;
}

.rbtl-actions-menu {
    display: inline-block;
    min-width: 112px;
    text-align: left;
}

.rbtl-actions-menu summary {
    background: #ffffff;
    border: 1px solid rgba(28, 27, 25, 0.14);
    border-radius: 8px;
    color: #1c1b19;
    cursor: pointer;
    display: block;
    font-size: 13px;
    font-weight: 800;
    list-style: none;
    min-height: 34px;
    padding: 8px 12px;
    text-align: center;
}

.rbtl-actions-menu summary::-webkit-details-marker {
    display: none;
}

.rbtl-actions-menu[open] summary {
    background: #234a3e;
    border-color: #234a3e;
    color: #f6f4ef;
}

.rbtl-actions-menu div {
    background: #ffffff;
    border: 1px solid rgba(28, 27, 25, 0.12);
    border-radius: 8px;
    box-shadow: 0 16px 32px rgba(28, 27, 25, 0.1);
    display: grid;
    gap: 2px;
    margin-top: 8px;
    padding: 6px;
}

.rbtl-actions-menu a,
.rbtl-actions-menu button {
    background: transparent;
    border: none;
    border-radius: 6px;
    color: #1c1b19;
    cursor: pointer;
    display: block;
    font: inherit;
    font-size: 13px;
    font-weight: 800;
    padding: 9px 10px;
    text-align: left;
    text-decoration: none;
    width: 100%;
}

.rbtl-actions-menu a:hover,
.rbtl-actions-menu button:hover {
    background: #f3f1eb;
}

.rbtl-actions-menu button {
    color: #a34032;
}

.rbtl-empty-cell {
    color: #6b6862 !important;
    padding: 34px 14px !important;
    text-align: center;
}

.rbtl-pagination {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 0 24px 24px;
}

.rbtl-pagination a {
    border: 1px solid rgba(28, 27, 25, 0.12);
    border-radius: 8px;
    color: #1c1b19;
    font-size: 13px;
    font-weight: 800;
    padding: 8px 11px;
    text-decoration: none;
}

.rbtl-pagination a.active {
    background: #234a3e;
    color: #f6f4ef;
}

.rbtl-pagination a.disabled {
    opacity: 0.45;
    pointer-events: none;
}

@media (max-width: 720px) {
    .rbtl-cert-hero,
    .rbtl-cert-toolbar {
        align-items: stretch;
        display: flex;
        flex-direction: column;
    }

    .rbtl-cert-hero-side {
        align-items: stretch;
        flex-direction: column;
    }

    .rbtl-cert-total {
        align-items: flex-start;
        border-right: none;
        border-bottom: 1px solid rgba(28, 27, 25, 0.12);
        padding: 0 0 12px;
    }

    .rbtl-cert-create {
        justify-content: center;
    }

    .rbtl-bulk-panel {
        align-items: stretch;
        flex-direction: column;
    }

    .rbtl-bulk-actions {
        justify-content: flex-start;
    }
}
</style>
