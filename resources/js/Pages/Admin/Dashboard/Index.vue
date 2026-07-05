<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps({
    metrics: {
        type: Object,
        required: true,
    },
    recentCertificates: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="RBTL Admin Dashboard" />

    <AdminLayout title="Dashboard">
        <div class="rbtl-dashboard-grid">
            <section class="rbtl-admin-card rbtl-metric">
                <span>Total certificates</span>
                <strong>{{ metrics.certificates }}</strong>
            </section>
            <section class="rbtl-admin-card rbtl-metric">
                <span>Active records</span>
                <strong>{{ metrics.activeCertificates }}</strong>
            </section>
            <section class="rbtl-admin-card rbtl-metric">
                <span>Inactive records</span>
                <strong>{{ metrics.inactiveCertificates }}</strong>
            </section>
            <section class="rbtl-admin-card rbtl-metric">
                <span>Contact messages</span>
                <strong>{{ metrics.contactSubmissions }}</strong>
            </section>
            <section class="rbtl-admin-card rbtl-metric">
                <span>New messages</span>
                <strong>{{ metrics.newContactSubmissions }}</strong>
            </section>
            <section class="rbtl-admin-card rbtl-metric">
                <span>Admin users</span>
                <strong>{{ metrics.admins }}</strong>
            </section>
        </div>

        <section class="rbtl-admin-card rbtl-dashboard-section">
            <div class="rbtl-dashboard-section-head">
                <div>
                    <span>Certificate Registry</span>
                    <h2>Recent certificates</h2>
                </div>
                <Link class="rbtl-admin-action" href="/rbtl/certificates/create">Add Certificate</Link>
            </div>

            <div v-if="recentCertificates.length" class="rbtl-recent-list">
                <div v-for="certificate in recentCertificates" :key="certificate.id" class="rbtl-recent-row">
                    <div>
                        <strong>{{ certificate.certificate_number }}</strong>
                        <span>{{ certificate.customer_name || 'No customer name' }}</span>
                    </div>
                    <div>
                        <span>{{ certificate.issued_at || 'No issue date' }}</span>
                        <em :class="{ inactive: !certificate.is_active }">
                            {{ certificate.is_active ? 'Active' : 'Inactive' }}
                        </em>
                    </div>
                </div>
            </div>

            <p v-else class="rbtl-admin-empty">No certificates have been added yet.</p>
        </section>

        <section class="rbtl-admin-card rbtl-dashboard-section">
            <div class="rbtl-dashboard-section-head">
                <div>
                    <span>Next Modules</span>
                    <h2>Content and media management</h2>
                </div>
            </div>
            <p class="rbtl-admin-muted">
                The admin shell is ready for content blocks, media library, and settings modules. Certificate management is the first dynamic module.
            </p>
        </section>
    </AdminLayout>
</template>

<style>
.rbtl-dashboard-grid {
    display: grid;
    gap: 18px;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    margin-bottom: 22px;
}

.rbtl-metric {
    padding: 22px;
}

.rbtl-metric span,
.rbtl-dashboard-section-head span {
    color: #6b6862;
    display: block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
}

.rbtl-metric strong {
    display: block;
    font-family: 'Cormorant Garamond', serif;
    font-size: 44px;
    line-height: 1;
    margin-top: 12px;
}

.rbtl-dashboard-section {
    margin-top: 22px;
    padding: 24px;
}

.rbtl-dashboard-section-head {
    align-items: center;
    display: flex;
    gap: 18px;
    justify-content: space-between;
    margin-bottom: 20px;
}

.rbtl-dashboard-section-head h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 30px;
    line-height: 1;
    margin: 6px 0 0;
}

.rbtl-recent-list {
    border: 1px solid rgba(28, 27, 25, 0.08);
    border-radius: 8px;
    overflow: hidden;
}

.rbtl-recent-row {
    align-items: center;
    display: flex;
    gap: 18px;
    justify-content: space-between;
    padding: 15px 16px;
}

.rbtl-recent-row + .rbtl-recent-row {
    border-top: 1px solid rgba(28, 27, 25, 0.08);
}

.rbtl-recent-row div {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.rbtl-recent-row div:last-child {
    align-items: flex-end;
}

.rbtl-recent-row strong {
    font-size: 14px;
}

.rbtl-recent-row span,
.rbtl-admin-muted,
.rbtl-admin-empty {
    color: #6b6862;
    font-size: 14px;
    line-height: 1.6;
}

.rbtl-recent-row em {
    color: #234a3e;
    font-size: 12px;
    font-style: normal;
    font-weight: 800;
}

.rbtl-recent-row em.inactive {
    color: #b24a3e;
}

@media (max-width: 800px) {
    .rbtl-dashboard-grid {
        grid-template-columns: 1fr;
    }

    .rbtl-dashboard-section-head,
    .rbtl-recent-row {
        align-items: flex-start;
        flex-direction: column;
    }

    .rbtl-recent-row div:last-child {
        align-items: flex-start;
    }
}
</style>
