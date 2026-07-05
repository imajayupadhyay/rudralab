<script setup>
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import CertificateForm from './Partials/CertificateForm.vue';

defineProps({
    certificate: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head title="Add Certificate" />

    <AdminLayout title="Add Certificate" eyebrow="Certificate Registry">
        <CertificateForm :certificate="certificate" mode="create" />
    </AdminLayout>
</template>
