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
    <Head title="Add Admin User" />

    <AdminLayout title="Add Admin" eyebrow="Admin Accounts">
        <UserForm :admin-user="adminUser" mode="create" />
    </AdminLayout>
</template>
