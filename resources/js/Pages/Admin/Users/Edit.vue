<script setup>
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import UserForm from './Partials/UserForm.vue';

defineProps({
    adminUser: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head title="Edit Admin User" />

    <AdminLayout title="Edit Admin" eyebrow="Admin Accounts">
        <UserForm :admin-user="adminUser" mode="edit" />
    </AdminLayout>
</template>
