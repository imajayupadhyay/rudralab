<script setup>
import { reactive } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    submissions: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({ search: '', status: '' }),
    },
});

const form = reactive({
    search: props.filters.search || '',
    status: props.filters.status || '',
});

const search = () => {
    router.get('/rbtl/contact-submissions', { search: form.search, status: form.status }, {
        preserveState: true,
        replace: true,
    });
};

const resetSearch = () => {
    form.search = '';
    form.status = '';
    router.get('/rbtl/contact-submissions', {}, { replace: true });
};

const destroySubmission = (submission) => {
    if (!window.confirm(`Delete message from ${submission.name}?`)) {
        return;
    }

    router.delete(`/rbtl/contact-submissions/${submission.id}`);
};
</script>

<template>
    <Head title="Contact Form Leads" />

    <AdminLayout title="Form Leads" eyebrow="Contact">
        <section class="rbtl-admin-card rbtl-leads-index">
            <div class="rbtl-leads-head">
                <div>
                    <span>Submissions</span>
                    <h2>Contact form messages</h2>
                </div>
            </div>

            <form class="rbtl-leads-search" @submit.prevent="search">
                <input v-model="form.search" type="search" placeholder="Search name, phone, email, or message" />
                <select v-model="form.status">
                    <option value="">All Statuses</option>
                    <option value="new">New</option>
                    <option value="read">Read</option>
                </select>
                <button type="submit">Search</button>
                <button type="button" class="secondary" @click="resetSearch">Reset</button>
            </form>

            <div class="rbtl-leads-table-wrap">
                <table class="rbtl-leads-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Received</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="submission in submissions.data" :key="submission.id">
                            <td><strong>{{ submission.name }}</strong></td>
                            <td>
                                <span>{{ submission.phone || '-' }}</span>
                                <small>{{ submission.email || '-' }}</small>
                            </td>
                            <td>{{ submission.message }}</td>
                            <td>
                                <span class="rbtl-lead-status" :class="{ read: submission.status === 'read' }">
                                    {{ submission.status }}
                                </span>
                            </td>
                            <td>{{ submission.created_at }}</td>
                            <td class="rbtl-row-actions">
                                <Link :href="`/rbtl/contact-submissions/${submission.id}`">View</Link>
                                <button type="button" @click="destroySubmission(submission)">Delete</button>
                            </td>
                        </tr>
                        <tr v-if="!submissions.data.length">
                            <td colspan="6" class="rbtl-empty-cell">No contact submissions found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="submissions.links?.length > 3" class="rbtl-pagination">
                <Link
                    v-for="link in submissions.links"
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
.rbtl-leads-index {
    padding: 24px;
}

.rbtl-leads-head {
    align-items: center;
    display: flex;
    gap: 18px;
    justify-content: space-between;
    margin-bottom: 22px;
}

.rbtl-leads-head span {
    color: #6b6862;
    display: block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
}

.rbtl-leads-head h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 32px;
    line-height: 1;
    margin: 6px 0 0;
}

.rbtl-leads-search {
    display: grid;
    gap: 12px;
    grid-template-columns: minmax(0, 1fr) 160px auto auto;
    margin-bottom: 20px;
}

.rbtl-leads-search input,
.rbtl-leads-search select {
    background: #fbfaf7;
    border: 1px solid rgba(28, 27, 25, 0.14);
    border-radius: 8px;
    color: #1c1b19;
    font: inherit;
    padding: 12px 14px;
}

.rbtl-leads-search input:focus,
.rbtl-leads-search select:focus {
    border-color: #234a3e;
    box-shadow: 0 0 0 3px rgba(35, 74, 62, 0.12);
    outline: none;
}

.rbtl-leads-search button,
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

.rbtl-leads-search button.secondary {
    background: transparent;
    border: 1px solid rgba(28, 27, 25, 0.18);
    color: #1c1b19;
}

.rbtl-leads-table-wrap {
    overflow-x: auto;
}

.rbtl-leads-table {
    border-collapse: collapse;
    min-width: 920px;
    width: 100%;
}

.rbtl-leads-table th {
    background: #fbfaf7;
    color: #6b6862;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.12em;
    padding: 13px 14px;
    text-align: left;
    text-transform: uppercase;
}

.rbtl-leads-table td {
    border-top: 1px solid rgba(28, 27, 25, 0.08);
    color: #4a4842;
    font-size: 14px;
    padding: 15px 14px;
    vertical-align: top;
}

.rbtl-leads-table strong {
    color: #1c1b19;
}

.rbtl-leads-table small {
    color: #6b6862;
    display: block;
    margin-top: 4px;
}

.rbtl-lead-status {
    color: #b24a3e;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
}

.rbtl-lead-status.read {
    color: #234a3e;
}

.rbtl-row-actions {
    text-align: right;
    white-space: nowrap;
}

.rbtl-row-actions a,
.rbtl-row-actions button {
    display: inline-flex;
    margin-left: 8px;
    text-decoration: none;
}

.rbtl-row-actions a {
    color: #234a3e;
    font-size: 13px;
    font-weight: 800;
}

.rbtl-row-actions button {
    background: #b24a3e;
    padding: 8px 11px;
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

@media (max-width: 760px) {
    .rbtl-leads-search {
        grid-template-columns: 1fr;
    }
}
</style>
