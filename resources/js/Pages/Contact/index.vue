<script setup>
import { computed } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import ContactGrid from './components/ContactGrid.vue';
import HoursBanner from './components/HoursBanner.vue';
import PageHero from './components/PageHero.vue';
import SiteFooter from '@/global/SiteFooter.vue';
import SiteHeader from '@/global/SiteHeader.vue';

defineProps({
    content: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const sent = computed(() => Boolean(page.props.flash?.success));

const form = useForm({
    name: '',
    phone: '',
    email: '',
    message: '',
});

const submit = () => {
    form.post('/contact', {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Contact RBTL" />

    <div class="vgtl-contact-page">
        <SiteHeader />

        <main style="flex:1;">
            <PageHero :content="content.hero" />
            <ContactGrid
                :form="form"
                :info="content.info"
                :content="content.form"
                :address-card="content.address_card"
                :sent="sent"
                :processing="form.processing"
                :errors="form.errors"
                @submit="submit"
            />
            <HoursBanner :content="content.hours" />
        </main>

        <SiteFooter />
    </div>
</template>

<style>
body {
    margin: 0;
}

::selection {
    background: #234a3e;
    color: #f6f4ef;
}

.vgtl-contact-page {
    background: #f6f4ef;
    color: #1c1b19;
    display: flex;
    flex-direction: column;
    font-family: 'Manrope', sans-serif;
    min-height: 100vh;
    overflow-x: hidden;
}

.vgtl-input:focus,
.vgtl-area:focus {
    outline: none;
    border-color: #234a3e !important;
    box-shadow: 0 0 0 3px rgba(35, 74, 62, 0.12);
}

@media (max-width: 900px) {
    .vgtl-desktop-nav {
        display: none !important;
    }

    .vgtl-burger {
        display: flex !important;
    }

    .vgtl-contact-grid {
        grid-template-columns: 1fr !important;
        gap: 40px !important;
    }

    .vgtl-info-grid {
        grid-template-columns: 1fr !important;
    }

    .vgtl-footer {
        grid-template-columns: 1fr !important;
        gap: 32px !important;
    }
}

@media (max-width: 560px) {
    .vgtl-name-row {
        grid-template-columns: 1fr !important;
    }
}
</style>
