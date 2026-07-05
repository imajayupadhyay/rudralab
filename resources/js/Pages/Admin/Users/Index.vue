<script setup>
import { reactive } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    users: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({ search: '' }),
    },
    adminCount: {
        type: Number,
        required: true,
    },
});

const form = reactive({
    search: props.filters.search || '',
});

const search = () => {
    router.get('/rbtl/users', { search: form.search }, {
        preserveState: true,
        replace: true,
    });
};

const resetSearch = () => {
    form.search = '';
    router.get('/rbtl/users', {}, { replace: true });
};

const destroyUser = (adminUser) => {
    if (!window.confirm(`Delete admin ${adminUser.name}?`)) {
        return;
    }

    router.delete(`/rbtl/users/${adminUser.id}`);
};
</script>

<template>
    <Head title="Admin Users" />

    <AdminLayout title="Users" eyebrow="Admin Accounts">
        <section class="rbtl-admin-card rbtl-users-index">
            <div class="rbtl-users-head">
                <div>
                    <span>{{ adminCount }} admin accounts</span>
                    <h2>Admin users</h2>
                </div>
                <Link class="rbtl-admin-action" href="/rbtl/users/create">Add Admin</Link>
            </div>

            <form class="rbtl-users-search" @submit.prevent="search">
                <input v-model="form.search" type="search" placeholder="Search name or email" />
                <button type="submit">Search</button>
                <button type="button" class="secondary" @click="resetSearch">Reset</button>
            </form>

            <div class="rbtl-users-table-wrap">
                <table class="rbtl-users-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Verified</th>
                            <th>Created</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="adminUser in users.data" :key="adminUser.id">
                            <td>
                                <strong>{{ adminUser.name }}</strong>
                                <small v-if="adminUser.is_current_user">Current account</small>
                            </td>
                            <td>{{ adminUser.email }}</td>
                            <td>{{ adminUser.email_verified_at || '-' }}</td>
                            <td>{{ adminUser.created_at || '-' }}</td>
                            <td class="rbtl-row-actions">
                                <Link :href="`/rbtl/users/${adminUser.id}/edit`">Edit</Link>
                                <button type="button" :disabled="adminUser.is_current_user" @click="destroyUser(adminUser)">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        <tr v-if="!users.data.length">
                            <td colspan="5" class="rbtl-empty-cell">No admin users found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="users.links?.length > 3" class="rbtl-pagination">
                <Link
                    v-for="link in users.links"
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
.rbtl-users-index {
    padding: 24px;
}

.rbtl-users-head {
    align-items: center;
    display: flex;
    gap: 18px;
    justify-content: space-between;
    margin-bottom: 22px;
}

.rbtl-users-head span {
    color: #6b6862;
    display: block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
}

.rbtl-users-head h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 32px;
    line-height: 1;
    margin: 6px 0 0;
}

.rbtl-users-search {
    display: grid;
    gap: 12px;
    grid-template-columns: minmax(0, 1fr) auto auto;
    margin-bottom: 20px;
}

.rbtl-users-search input {
    background: #fbfaf7;
    border: 1px solid rgba(28, 27, 25, 0.14);
    border-radius: 8px;
    color: #1c1b19;
    font: inherit;
    padding: 12px 14px;
}

.rbtl-users-search input:focus {
    border-color: #234a3e;
    box-shadow: 0 0 0 3px rgba(35, 74, 62, 0.12);
    outline: none;
}

.rbtl-users-search button,
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

.rbtl-users-search button.secondary {
    background: transparent;
    border: 1px solid rgba(28, 27, 25, 0.18);
    color: #1c1b19;
}

.rbtl-users-table-wrap {
    overflow-x: auto;
}

.rbtl-users-table {
    border-collapse: collapse;
    min-width: 780px;
    width: 100%;
}

.rbtl-users-table th {
    background: #fbfaf7;
    color: #6b6862;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.12em;
    padding: 13px 14px;
    text-align: left;
    text-transform: uppercase;
}

.rbtl-users-table td {
    border-top: 1px solid rgba(28, 27, 25, 0.08);
    color: #4a4842;
    font-size: 14px;
    padding: 15px 14px;
    vertical-align: middle;
}

.rbtl-users-table strong {
    color: #1c1b19;
}

.rbtl-users-table small {
    color: #234a3e;
    display: block;
    font-size: 12px;
    font-weight: 800;
    margin-top: 4px;
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

.rbtl-row-actions button:disabled {
    cursor: not-allowed;
    opacity: 0.45;
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
    .rbtl-users-head,
    .rbtl-users-search {
        align-items: stretch;
        display: flex;
        flex-direction: column;
    }
}
</style>
