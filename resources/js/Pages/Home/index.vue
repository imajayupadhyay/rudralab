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

defineProps({
    content: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head title="RBTL Rudra Beads & Gems Testing Lab" />

    <div class="vgtl-page">
        <SiteHeader />

        <main id="top">
            <HeroSection :content="content.hero" />
            <StatsStrip :stats="content.stats" />
            <WelcomeSection :content="content.welcome" />
            <AuthenticationBand :content="content.testing" />
            <VisionMissionSection :content="content.vision_mission" />
            <ServicesSection :content="content.services" />
            <CtaSection :content="content.cta" />
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
