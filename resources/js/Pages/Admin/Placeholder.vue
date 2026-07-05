<script setup>
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head :title="title" />

    <AdminLayout :title="title">
        <section class="rbtl-admin-card rbtl-placeholder">
            <span>Planned Module</span>
            <h2>{{ title }}</h2>
            <p>{{ description }}</p>
        </section>
    </AdminLayout>
</template>

<style>
.rbtl-placeholder {
    padding: 28px;
}

.rbtl-placeholder span {
    color: #6b6862;
    display: block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
}

.rbtl-placeholder h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 34px;
    line-height: 1;
    margin: 8px 0 14px;
}

.rbtl-placeholder p {
    color: #6b6862;
    font-size: 15px;
    line-height: 1.7;
    margin: 0;
    max-width: 720px;
}
</style>
