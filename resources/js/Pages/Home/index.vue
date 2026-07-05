<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticationBand from './components/AuthenticationBand.vue';
import CtaSection from './components/CtaSection.vue';
import HeroSection from './components/HeroSection.vue';
import ServicesSection from './components/ServicesSection.vue';
import SiteFooter from '@/global/SiteFooter.vue';
import SiteHeader from '@/global/SiteHeader.vue';
import StatsStrip from './components/StatsStrip.vue';
import VisionMissionSection from './components/VisionMissionSection.vue';
import WelcomeSection from './components/WelcomeSection.vue';

const stats = [
    { value: 'RBTL', label: 'Dedicated Rudra beads & gems testing lab' },
    { value: '5+', label: 'Quality and identity checks' },
    { value: 'X-Ray', label: 'Mukhi structure analysis' },
    { value: '100%', label: 'Integrity & transparency' },
];

const checklist = [
    'Rudraksha Beads & Gems Testing',
    'Mukhi Identification',
    'X-Ray Structure Review',
    'Natural Origin Assessment',
    'Certification & Report Verification',
];

const mission = [
    'Provide accurate and dependable Rudra beads & gems certification',
    'Make Rudraksha testing accessible to everyone',
    'Maintain highest standards of integrity and transparency',
    'Educate customers about authentic Rudraksha beads',
    'Preserve trust in natural Rudra beads and spiritual products',
];

const vision = [
    "Become India's most trusted Rudra beads & gems testing lab",
    'Set new standards in Rudraksha certification services',
    'Bridge traditional faith with modern scientific testing',
    'Expand reliable bead testing services with transparent reporting',
];

const services = [
    {
        title: 'Rudra Beads & Gems Testing',
        desc: 'Detailed testing for Rudraksha beads, gemstones, bead structure, surface features, and identity markers using careful laboratory procedures.',
        img: '/images/rbtl/service-testing.png',
    },
    {
        title: 'Mukhi Verification',
        desc: 'Mukhi counting and verification supported by visual inspection, X-Ray review, and structured certificate records.',
        img: '/images/rbtl/service-mukhi.png',
    },
    {
        title: 'Certificate Registry',
        desc: 'Each issued certificate can be checked through the RBTL registry so customers can confirm report details with confidence.',
        img: '/images/rbtl/service-certificate.png',
    },
];
</script>

<template>
    <Head title="RBTL Rudra Beads & Gems Testing Lab" />

    <div class="vgtl-page">
        <SiteHeader />

        <main id="top">
            <HeroSection />
            <StatsStrip :stats="stats" />
            <WelcomeSection />
            <AuthenticationBand :checklist="checklist" />
            <VisionMissionSection :mission="mission" :vision="vision" />
            <ServicesSection :services="services" />
            <CtaSection />
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

.vgtl-page {
    background: #f6f4ef;
    color: #1c1b19;
    font-family: 'Manrope', sans-serif;
    min-height: 100vh;
    overflow-x: hidden;
}

@media (max-width: 900px) {
    .vgtl-desktop-nav {
        display: none !important;
    }

    .vgtl-burger {
        display: flex !important;
    }

    .vgtl-hero {
        grid-template-columns: 1fr !important;
        gap: 40px !important;
        padding-top: 60px !important;
    }

    .vgtl-two {
        grid-template-columns: 1fr !important;
        gap: 36px !important;
    }

    .vgtl-stats {
        grid-template-columns: 1fr 1fr !important;
    }

    .vgtl-checks {
        grid-template-columns: 1fr 1fr !important;
    }

    .vgtl-cards {
        grid-template-columns: 1fr !important;
    }

    .vgtl-footer {
        grid-template-columns: 1fr !important;
        gap: 32px !important;
    }
}

@media (max-width: 560px) {
    .vgtl-stats {
        grid-template-columns: 1fr !important;
    }

    .vgtl-checks {
        grid-template-columns: 1fr !important;
    }
}
</style>
