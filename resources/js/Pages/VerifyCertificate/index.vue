<script setup>
import { computed, onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import CertificateForm from './components/CertificateForm.vue';
import HelpStrip from './components/HelpStrip.vue';
import PageHero from './components/PageHero.vue';
import ResultCard from './components/ResultCard.vue';
import SiteFooter from './components/SiteFooter.vue';
import SiteHeader from './components/SiteHeader.vue';

const query = ref('');
const status = ref('idle');
const searched = ref('');

const certificates = {
    'VGTL/GEM/211554': {
        number: 'VGTL/GEM/211554',
        issued: '18 Feb 2026',
        image: '/images/rbtl/service-mukhi.png',
        fields: [
            { k: 'Certificate', v: 'VGTL/GEM/211554' },
            { k: 'Weight', v: '25-30 GMS' },
            { k: 'Shape/Cut', v: 'ROUND BEAD' },
            { k: 'Dimension', v: '8MM mm' },
            { k: 'Colour', v: 'BLACK' },
            { k: 'Refractive Index', v: 'N/A' },
            { k: 'Specific Gravity', v: 'N/A' },
            { k: 'Remarks', v: 'KARUNGALI BRACELET' },
        ],
    },
};

const helps = [
    { n: '01', t: 'Find the number printed on your RBTL certificate' },
    { n: '02', t: 'Enter it above exactly as shown' },
    { n: '03', t: 'Confirm the details match your Rudra bead' },
];

const normalize = (value) => (value || '').trim().toUpperCase();

const verificationUrl = computed(() => {
    if (!result.value.number || typeof window === 'undefined') {
        return '';
    }

    const url = new URL('/verify-certificate', window.location.origin);
    url.searchParams.set('certificate', result.value.number);

    return url.toString();
});

const syncUrl = (certificateNumber) => {
    if (typeof window === 'undefined') {
        return;
    }

    const url = new URL(window.location.href);
    url.searchParams.set('certificate', certificateNumber);
    window.history.replaceState({}, '', url.toString());
};

const verify = () => {
    const normalized = normalize(query.value);

    if (!normalized) {
        status.value = 'empty';
        searched.value = '';
        return;
    }

    searched.value = normalized;
    status.value = certificates[normalized] ? 'found' : 'notfound';

    if (certificates[normalized]) {
        syncUrl(certificates[normalized].number);
    }
};

const fillSample = () => {
    query.value = 'VGTL/GEM/211554';
    verify();
};

const result = computed(() => certificates[normalize(searched.value)] || {
    number: '',
    issued: '',
    image: '',
    fields: [],
});

onMounted(() => {
    const certificateNumber = new URLSearchParams(window.location.search).get('certificate');

    if (certificateNumber) {
        query.value = certificateNumber;
        verify();
    }
});
</script>

<template>
    <Head title="Verify RBTL Certificate" />

    <div class="vgtl-verify-page">
        <SiteHeader />

        <main style="flex:1;">
            <PageHero />
            <CertificateForm
                v-model="query"
                :show-hint="status === 'empty'"
                :show-try="status === 'idle' || status === 'empty' || status === 'notfound'"
                @fill-sample="fillSample"
                @verify="verify"
            />
            <ResultCard
                v-if="status === 'found'"
                :result="result"
                :verification-url="verificationUrl"
            />
            <ResultCard
                v-if="status === 'notfound'"
                :searched="searched"
                not-found
            />
            <HelpStrip :helps="helps" />
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

.vgtl-verify-page {
    background: #f6f4ef;
    color: #1c1b19;
    display: flex;
    flex-direction: column;
    font-family: 'Manrope', sans-serif;
    min-height: 100vh;
    overflow-x: hidden;
}

.vgtl-input:focus {
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

    .vgtl-result-grid {
        grid-template-columns: 1fr !important;
    }

    .vgtl-certificate-layout {
        grid-template-columns: 1fr !important;
    }

    .vgtl-footer {
        grid-template-columns: 1fr !important;
        gap: 32px !important;
    }
}

@media (max-width: 560px) {
    .vgtl-form-row {
        align-items: stretch !important;
        flex-direction: column !important;
    }

    .vgtl-certificate-row {
        grid-template-columns: 1fr !important;
        gap: 6px !important;
    }
}
</style>
