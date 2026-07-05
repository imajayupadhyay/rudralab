<script setup>
import { reactive } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
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
            <div class="rbtl-cert-index-head">
                <div>
                    <span>Registry</span>
                    <h2>Certificate records</h2>
                </div>
                <Link class="rbtl-admin-action" href="/rbtl/certificates/create">Add Certificate</Link>
            </div>

            <form class="rbtl-cert-search" @submit.prevent="search">
                <input v-model="form.search" type="search" placeholder="Search certificate, customer, origin, remarks" />
                <button type="submit">Search</button>
                <button type="button" class="secondary" @click="resetSearch">Reset</button>
            </form>

            <div class="rbtl-cert-table-wrap">
                <table class="rbtl-cert-table">
                    <thead>
                        <tr>
                            <th>Certificate</th>
                            <th>Customer</th>
                            <th>Issued</th>
                            <th>Origin</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="certificate in certificates.data" :key="certificate.id">
                            <td>
                                <strong>{{ certificate.certificate_number }}</strong>
                            </td>
                            <td>{{ certificate.customer_name || '-' }}</td>
                            <td>{{ certificate.issued_at || '-' }}</td>
                            <td>{{ certificate.origin || '-' }}</td>
                            <td>
                                <span class="rbtl-status" :class="{ inactive: !certificate.is_active }">
                                    {{ certificate.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="rbtl-row-actions">
                                <Link class="duplicate" :href="`/rbtl/certificates/${certificate.id}/duplicate`">Duplicate</Link>
                                <Link class="edit" :href="`/rbtl/certificates/${certificate.id}/edit`">Edit</Link>
                                <button type="button" @click="destroyCertificate(certificate)">Delete</button>
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
    padding: 24px;
}

.rbtl-cert-index-head {
    align-items: center;
    display: flex;
    gap: 18px;
    justify-content: space-between;
    margin-bottom: 22px;
}

.rbtl-cert-index-head span {
    color: #6b6862;
    display: block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
}

.rbtl-cert-index-head h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 32px;
    line-height: 1;
    margin: 6px 0 0;
}

.rbtl-cert-search {
    display: grid;
    gap: 12px;
    grid-template-columns: minmax(0, 1fr) auto auto;
    margin-bottom: 20px;
}

.rbtl-cert-search input {
    background: #fbfaf7;
    border: 1px solid rgba(28, 27, 25, 0.14);
    border-radius: 8px;
    color: #1c1b19;
    font: inherit;
    padding: 12px 14px;
}

.rbtl-cert-search input:focus {
    border-color: #234a3e;
    box-shadow: 0 0 0 3px rgba(35, 74, 62, 0.12);
    outline: none;
}

.rbtl-cert-search button,
.rbtl-row-actions button {
    background: #234a3e;
    border: none;
    border-radius: 8px;
    color: #f6f4ef;
    cursor: pointer;
    font: inherit;
    font-size: 13px;
    font-weight: 800;
    padding: 11px 15px;
}

.rbtl-cert-search button.secondary {
    background: transparent;
    border: 1px solid rgba(28, 27, 25, 0.18);
    color: #1c1b19;
}

.rbtl-cert-table-wrap {
    overflow-x: auto;
}

.rbtl-cert-table {
    border-collapse: collapse;
    min-width: 820px;
    width: 100%;
}

.rbtl-cert-table th {
    background: #fbfaf7;
    color: #6b6862;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.12em;
    padding: 13px 14px;
    text-align: left;
    text-transform: uppercase;
}

.rbtl-cert-table td {
    border-top: 1px solid rgba(28, 27, 25, 0.08);
    color: #4a4842;
    font-size: 14px;
    padding: 15px 14px;
    vertical-align: middle;
}

.rbtl-cert-table strong {
    color: #1c1b19;
}

.rbtl-status {
    color: #234a3e;
    font-size: 12px;
    font-weight: 800;
}

.rbtl-status.inactive {
    color: #b24a3e;
}

.rbtl-row-actions {
    text-align: right;
    white-space: nowrap;
}

.rbtl-row-actions a,
.rbtl-row-actions button {
    align-items: center;
    border-radius: 8px;
    box-sizing: border-box;
    display: inline-flex;
    font-size: 13px;
    font-weight: 800;
    justify-content: center;
    margin-left: 8px;
    min-height: 34px;
    padding: 8px 12px;
    text-decoration: none;
}

.rbtl-row-actions a {
    border: 1px solid rgba(35, 74, 62, 0.2);
    color: #234a3e;
}

.rbtl-row-actions a.duplicate {
    background: rgba(35, 74, 62, 0.1);
}

.rbtl-row-actions a.edit {
    background: #234a3e;
    color: #f6f4ef;
}

.rbtl-row-actions a:hover {
    transform: translateY(-1px);
}

.rbtl-row-actions button {
    background: #b24a3e;
    padding: 8px 12px;
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
    margin-top: 20px;
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
    .rbtl-cert-index-head,
    .rbtl-cert-search {
        align-items: stretch;
        display: flex;
        flex-direction: column;
    }
}
</style>
