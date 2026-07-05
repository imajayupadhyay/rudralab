<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const fallback = {
    brand: {
        mark: 'R',
        title: 'RBTL Rudra Beads & Gems Testing Lab',
        description: 'A dedicated Rudra beads & gems testing lab safeguarding authenticity, trust, and clear certification records.',
    },
    navigation: {
        heading: 'Navigate',
        links: [
            { label: 'Home', url: '/' },
            { label: 'Verify Certificate', url: '/verify-certificate' },
            { label: 'Contact Us', url: '/contact' },
        ],
    },
    services: {
        heading: 'Services',
        items: [
            { label: 'Rudra Beads & Gems Testing' },
            { label: 'Mukhi Verification' },
            { label: 'Certificate Registry' },
        ],
    },
    legal: {
        copyright: '2026 RBTL Rudra Beads & Gems Testing Lab. All rights reserved.',
        tagline: 'Authentic beads. Transparent testing.',
    },
};

const footer = computed(() => page.props.footer || fallback);
const brand = computed(() => footer.value.brand || fallback.brand);
const navigation = computed(() => footer.value.navigation || fallback.navigation);
const services = computed(() => footer.value.services || fallback.services);
const legal = computed(() => footer.value.legal || fallback.legal);
</script>

<template>
    <footer style="border-top:1px solid rgba(28,27,25,0.09);">
        <div
            style="max-width:1200px;margin:0 auto;padding:64px 32px 40px;display:grid;grid-template-columns:1.4fr 1fr 1fr;gap:48px;"
            class="vgtl-footer"
        >
            <div>
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:18px;">
                    <span
                        style="display:grid;place-items:center;width:38px;height:38px;border:1px solid #234A3E;border-radius:50%;font-family:'Cormorant Garamond',serif;font-weight:600;font-size:19px;color:#234A3E;"
                    >{{ brand.mark || fallback.brand.mark }}</span>
                    <span
                        style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:600;"
                    >{{ brand.title || fallback.brand.title }}</span>
                </div>
                <p style="font-size:14px;line-height:1.7;color:#6B6862;max-width:400px;margin:0;">
                    {{ brand.description || fallback.brand.description }}
                </p>
            </div>

            <div>
                <div style="font-size:12px;letter-spacing:0.2em;text-transform:uppercase;color:#9A968D;margin-bottom:18px;">
                    {{ navigation.heading || fallback.navigation.heading }}
                </div>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <a
                        v-for="(link, index) in navigation.links"
                        :key="`${link.url}-${index}`"
                        :href="link.url || '#'"
                        style="text-decoration:none;color:#1C1B19;font-size:14px;"
                    >{{ link.label }}</a>
                </div>
            </div>

            <div>
                <div style="font-size:12px;letter-spacing:0.2em;text-transform:uppercase;color:#9A968D;margin-bottom:18px;">
                    {{ services.heading || fallback.services.heading }}
                </div>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <span
                        v-for="(item, index) in services.items"
                        :key="`${item.label}-${index}`"
                        style="color:#6B6862;font-size:14px;"
                    >{{ item.label }}</span>
                </div>
            </div>
        </div>

        <div style="border-top:1px solid rgba(28,27,25,0.09);">
            <div
                style="max-width:1200px;margin:0 auto;padding:22px 32px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;"
            >
                <span style="font-size:12.5px;color:#9A968D;">
                    &copy; {{ legal.copyright || fallback.legal.copyright }}
                </span>
                <span style="font-size:12.5px;color:#9A968D;">{{ legal.tagline || fallback.legal.tagline }}</span>
            </div>
        </div>
    </footer>
</template>
