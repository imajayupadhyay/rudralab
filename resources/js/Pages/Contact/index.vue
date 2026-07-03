<script setup>
import { reactive, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import ContactGrid from './components/ContactGrid.vue';
import HoursBanner from './components/HoursBanner.vue';
import PageHero from './components/PageHero.vue';
import SiteFooter from './components/SiteFooter.vue';
import SiteHeader from './components/SiteHeader.vue';

const sent = ref(false);
const form = reactive({
    name: '',
    phone: '',
    email: '',
    message: '',
});

const info = [
    {
        icon: '⚑',
        label: 'Lab Location',
        value: 'Shiv Shakti Traders, near main Shaheed Udham Chowk, Guhla Road, Cheeka, Haryana',
    },
    {
        icon: '☎',
        label: 'Call Us',
        value: '+91 87085 40840\n+91 80598 72000',
    },
    {
        icon: '✉',
        label: 'Email Us',
        value: 'info@rbtl.test',
    },
    {
        icon: '◷',
        label: 'Working Hours',
        value: 'Mon – Sat  10:00am – 5:00pm\nSun  11:00am – 4:00pm',
    },
];

const submit = () => {
    sent.value = true;
};
</script>

<template>
    <Head title="Contact RBTL" />

    <div class="vgtl-contact-page">
        <SiteHeader />

        <main style="flex:1;">
            <PageHero />
            <ContactGrid
                :form="form"
                :info="info"
                :sent="sent"
                @submit="submit"
            />
            <HoursBanner />
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
