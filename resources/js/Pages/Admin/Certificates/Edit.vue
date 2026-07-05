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
    <Head title="Edit Certificate" />

    <AdminLayout title="Edit Certificate" eyebrow="Certificate Registry">
        <CertificateForm :certificate="certificate" mode="edit" />
    </AdminLayout>
</template>
