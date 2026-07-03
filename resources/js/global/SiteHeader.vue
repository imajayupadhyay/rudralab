<script setup>
import { computed, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const menuOpen = ref(false);

const currentPath = computed(() => {
    const url = page.url || '/';

    return url.split('?')[0].split('#')[0] || '/';
});

const isActive = (path) => currentPath.value === path;

const linkStyle = (path) => ({
    textDecoration: 'none',
    color: isActive(path) ? '#234A3E' : '#1C1B19',
    fontSize: '14px',
    fontWeight: isActive(path) ? '600' : '500',
});

const mobileLinkStyle = (path) => ({
    textDecoration: 'none',
    color: isActive(path) ? '#234A3E' : '#1C1B19',
    fontSize: '15px',
    fontWeight: isActive(path) ? '600' : '500',
});

const closeMenu = () => {
    menuOpen.value = false;
};
</script>

<template>
    <header
        style="position:sticky;top:0;z-index:50;background:rgba(246,244,239,0.82);backdrop-filter:blur(14px);border-bottom:1px solid rgba(28,27,25,0.09);"
    >
        <div
            style="max-width:1200px;margin:0 auto;padding:18px 32px;display:flex;align-items:center;justify-content:space-between;gap:24px;"
        >
            <a
                href="/"
                style="display:flex;align-items:center;gap:12px;text-decoration:none;color:inherit;"
                @click="closeMenu"
            >
                <span
                    style="display:grid;place-items:center;width:38px;height:38px;border:1px solid #234A3E;border-radius:50%;font-family:'Cormorant Garamond',serif;font-weight:600;font-size:19px;color:#234A3E;"
                >R</span>
                <span style="display:flex;flex-direction:column;line-height:1;">
                    <span
                        style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:600;letter-spacing:0.02em;"
                    >RBTL</span>
                    <span
                        style="font-size:9.5px;letter-spacing:0.28em;text-transform:uppercase;color:#6B6862;margin-top:3px;"
                    >Rudra Beads Testing Lab</span>
                </span>
            </a>

            <nav
                style="display:flex;align-items:center;gap:38px;"
                class="vgtl-desktop-nav"
            >
                <a href="/" :style="linkStyle('/')">Home</a>
                <a href="/verify-certificate" :style="linkStyle('/verify-certificate')">Verify Certificate</a>
                <a href="/contact" :style="linkStyle('/contact')">Contact Us</a>
                <a
                    href="/contact#form"
                    style="text-decoration:none;background:#234A3E;color:#F6F4EF;font-size:13px;font-weight:600;padding:10px 20px;border-radius:100px;"
                >Get Certified</a>
            </nav>

            <button
                aria-label="Menu"
                class="vgtl-burger"
                style="display:none;background:none;border:none;cursor:pointer;padding:8px;flex-direction:column;gap:5px;"
                type="button"
                @click="menuOpen = !menuOpen"
            >
                <span style="display:block;width:22px;height:1.5px;background:#1C1B19;"></span>
                <span style="display:block;width:22px;height:1.5px;background:#1C1B19;"></span>
                <span style="display:block;width:22px;height:1.5px;background:#1C1B19;"></span>
            </button>
        </div>

        <div
            v-if="menuOpen"
            style="border-top:1px solid rgba(28,27,25,0.09);padding:14px 32px 22px;display:flex;flex-direction:column;gap:16px;"
        >
            <a href="/" :style="mobileLinkStyle('/')" @click="closeMenu">Home</a>
            <a
                href="/verify-certificate"
                :style="mobileLinkStyle('/verify-certificate')"
                @click="closeMenu"
            >Verify Certificate</a>
            <a href="/contact" :style="mobileLinkStyle('/contact')" @click="closeMenu">Contact Us</a>
        </div>
    </header>
</template>
