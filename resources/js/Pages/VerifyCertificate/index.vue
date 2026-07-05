<script setup>
import { computed, ref, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import CertificateForm from './components/CertificateForm.vue';
import HelpStrip from './components/HelpStrip.vue';
import PageHero from './components/PageHero.vue';
import ResultCard from './components/ResultCard.vue';
import SiteFooter from '@/global/SiteFooter.vue';
import SiteHeader from '@/global/SiteHeader.vue';

const props = defineProps({
    content: {
        type: Object,
        required: true,
    },
    initialSearch: {
        type: String,
        default: '',
    },
    certificate: {
        type: Object,
        default: null,
    },
    certificateSearched: {
        type: String,
        default: '',
    },
});

const query = ref(props.initialSearch || '');
const emptySubmitted = ref(false);

const normalize = (value) => (value || '').trim().toUpperCase();

const searched = computed(() => props.certificateSearched || '');

const status = computed(() => {
    if (emptySubmitted.value) {
        return 'empty';
    }

    if (props.certificate) {
        return 'found';
    }

    if (searched.value) {
        return 'notfound';
    }

    return 'idle';
});

const verificationUrl = computed(() => {
    if (!props.certificate?.number || typeof window === 'undefined') {
        return '';
    }

    const url = new URL('/verify-certificate', window.location.origin);
    url.searchParams.set('certificate', props.certificate.number);

    return url.toString();
});

const verify = () => {
    const normalized = normalize(query.value);

    if (!normalized) {
        emptySubmitted.value = true;
        return;
    }

    emptySubmitted.value = false;

    router.get('/verify-certificate', { certificate: normalized }, {
        preserveScroll: true,
    });
};

const fillSample = () => {
    query.value = props.content.form.demo_certificate;
    verify();
};

const result = computed(() => props.certificate || {
    number: '',
    issued: '',
    image: '',
    fields: [],
});

watch(() => props.initialSearch, (value) => {
    query.value = value || '';
});
</script>

<template>
    <Head title="Verify RBTL Certificate" />

    <div class="vgtl-verify-page">
        <SiteHeader />

        <main style="flex:1;">
            <PageHero :content="content.hero" />
            <CertificateForm
                v-model="query"
                :content="content.form"
                :show-hint="status === 'empty'"
                :show-try="status === 'idle' || status === 'empty' || status === 'notfound'"
                @fill-sample="fillSample"
                @verify="verify"
            />
            <ResultCard
                v-if="status === 'found'"
                :content="content"
                :result="result"
                :verification-url="verificationUrl"
            />
            <ResultCard
                v-if="status === 'notfound'"
                :content="content"
                :searched="searched"
                not-found
            />
            <HelpStrip :helps="content.help" />
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
