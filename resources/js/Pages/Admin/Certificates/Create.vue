<script setup>
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import CertificateForm from './Partials/CertificateForm.vue';

defineProps({
    certificate: {
        type: Object,
        required: true,
    },
    duplicateSource: {
        type: Object,
        default: null,
    },
});
</script>

<template>
    <Head :title="duplicateSource ? 'Duplicate Certificate' : 'Add Certificate'" />

    <AdminLayout :title="duplicateSource ? 'Duplicate Certificate' : 'Add Certificate'" eyebrow="Certificate Registry">
        <CertificateForm :certificate="certificate" :duplicate-source="duplicateSource" :mode="duplicateSource ? 'duplicate' : 'create'" />
    </AdminLayout>
</template>
