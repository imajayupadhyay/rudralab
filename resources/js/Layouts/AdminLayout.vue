<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';

defineProps({
    title: {
        type: String,
        required: true,
    },
    eyebrow: {
        type: String,
        default: 'RBTL Admin',
    },
});

const page = usePage();
const sidebarOpen = ref(false);

const currentPath = computed(() => (page.url || '').split('?')[0]);
const user = computed(() => page.props.auth?.user || {});
const flash = computed(() => page.props.flash?.success || '');
const flashError = computed(() => page.props.flash?.error || '');

const navItems = [
    { label: 'Dashboard', href: '/rbtl/dashboard' },
    { label: 'Homepage', href: '/rbtl/homepage' },
    { label: 'Verify Page', href: '/rbtl/verify-certificate-page' },
    { label: 'Contact Page', href: '/rbtl/contact-page' },
    { label: 'Form Leads', href: '/rbtl/contact-submissions' },
    { label: 'Certificates', href: '/rbtl/certificates' },
    { label: 'Users', href: '/rbtl/users' },
];

const isActive = (href) => currentPath.value === href || currentPath.value.startsWith(`${href}/`);

const logout = () => {
    router.post('/rbtl/logout');
};
</script>

<template>
    <Head>
        <meta name="robots" content="noindex,nofollow,noarchive" />
    </Head>

    <div class="rbtl-admin-shell">
        <aside class="rbtl-admin-sidebar" :class="{ 'is-open': sidebarOpen }">
            <div class="rbtl-admin-brand">
                <span class="rbtl-admin-mark">R</span>
                <div>
                    <div class="rbtl-admin-brand-title">RBTL</div>
                    <div class="rbtl-admin-brand-subtitle">Admin Panel</div>
                </div>
            </div>

            <nav class="rbtl-admin-nav">
                <Link
                    v-for="item in navItems"
                    :key="item.href"
                    :href="item.href"
                    class="rbtl-admin-nav-link"
                    :class="{ active: isActive(item.href) }"
                    @click="sidebarOpen = false"
                >
                    {{ item.label }}
                </Link>
            </nav>

            <div class="rbtl-admin-sidebar-foot">
                <a href="/" class="rbtl-admin-public-link">View public site</a>
            </div>
        </aside>

        <div class="rbtl-admin-main">
            <header class="rbtl-admin-topbar">
                <button class="rbtl-admin-menu" type="button" @click="sidebarOpen = !sidebarOpen">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <div>
                    <div class="rbtl-admin-eyebrow">{{ eyebrow }}</div>
                    <h1>{{ title }}</h1>
                </div>

                <div class="rbtl-admin-user">
                    <div>
                        <strong>{{ user.name }}</strong>
                        <span>{{ user.email }}</span>
                    </div>
                    <button type="button" @click="logout">Logout</button>
                </div>
            </header>

            <main class="rbtl-admin-content">
                <div v-if="flash" class="rbtl-admin-flash">{{ flash }}</div>
                <div v-if="flashError" class="rbtl-admin-flash error">{{ flashError }}</div>
                <slot />
            </main>
        </div>
    </div>
</template>

<style>
body {
    margin: 0;
}

.rbtl-admin-shell {
    background: #f6f4ef;
    color: #1c1b19;
    display: grid;
    font-family: 'Manrope', sans-serif;
    grid-template-columns: 260px 1fr;
    min-height: 100vh;
}

.rbtl-admin-sidebar {
    background: #1f463b;
    color: #f6f4ef;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    padding: 26px 20px;
}

.rbtl-admin-brand {
    align-items: center;
    display: flex;
    gap: 12px;
    margin-bottom: 34px;
}

.rbtl-admin-mark {
    border: 1px solid rgba(246, 244, 239, 0.7);
    border-radius: 50%;
    display: grid;
    font-family: 'Cormorant Garamond', serif;
    font-size: 22px;
    font-weight: 700;
    height: 42px;
    place-items: center;
    width: 42px;
}

.rbtl-admin-brand-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 24px;
    font-weight: 700;
    line-height: 1;
}

.rbtl-admin-brand-subtitle {
    color: rgba(246, 244, 239, 0.62);
    font-size: 11px;
    letter-spacing: 0.16em;
    margin-top: 4px;
    text-transform: uppercase;
}

.rbtl-admin-nav {
    display: flex;
    flex: 1;
    flex-direction: column;
    gap: 7px;
}

.rbtl-admin-nav-link,
.rbtl-admin-public-link {
    border-radius: 8px;
    color: rgba(246, 244, 239, 0.78);
    font-size: 14px;
    font-weight: 700;
    padding: 12px 13px;
    text-decoration: none;
}

.rbtl-admin-nav-link.active,
.rbtl-admin-nav-link:hover,
.rbtl-admin-public-link:hover {
    background: rgba(246, 244, 239, 0.12);
    color: #ffffff;
}

.rbtl-admin-sidebar-foot {
    border-top: 1px solid rgba(246, 244, 239, 0.14);
    padding-top: 18px;
}

.rbtl-admin-main {
    min-width: 0;
}

.rbtl-admin-topbar {
    align-items: center;
    background: rgba(246, 244, 239, 0.9);
    border-bottom: 1px solid rgba(28, 27, 25, 0.09);
    display: flex;
    gap: 22px;
    justify-content: space-between;
    padding: 22px 32px;
    position: sticky;
    top: 0;
    z-index: 20;
}

.rbtl-admin-eyebrow {
    color: #6b6862;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.16em;
    text-transform: uppercase;
}

.rbtl-admin-topbar h1 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 34px;
    line-height: 1;
    margin: 6px 0 0;
}

.rbtl-admin-user {
    align-items: center;
    display: flex;
    gap: 16px;
}

.rbtl-admin-user div {
    display: flex;
    flex-direction: column;
    text-align: right;
}

.rbtl-admin-user strong {
    font-size: 14px;
}

.rbtl-admin-user span {
    color: #6b6862;
    font-size: 12px;
}

.rbtl-admin-user button,
.rbtl-admin-action {
    background: #234a3e;
    border: none;
    border-radius: 8px;
    color: #f6f4ef;
    cursor: pointer;
    font-family: inherit;
    font-size: 13px;
    font-weight: 800;
    padding: 11px 16px;
    text-decoration: none;
}

.rbtl-admin-content {
    padding: 30px 32px 54px;
}

.rbtl-admin-flash {
    background: #e9f2ec;
    border: 1px solid rgba(35, 74, 62, 0.18);
    border-radius: 8px;
    color: #234a3e;
    font-size: 14px;
    font-weight: 800;
    margin-bottom: 18px;
    padding: 13px 15px;
}

.rbtl-admin-flash.error {
    background: #fbf0ee;
    border-color: rgba(178, 74, 62, 0.25);
    color: #b24a3e;
}

.rbtl-admin-card {
    background: #ffffff;
    border: 1px solid rgba(28, 27, 25, 0.1);
    border-radius: 8px;
    box-shadow: 0 18px 48px rgba(35, 74, 62, 0.06);
}

.rbtl-admin-menu {
    background: transparent;
    border: none;
    cursor: pointer;
    display: none;
    flex-direction: column;
    gap: 5px;
    padding: 6px;
}

.rbtl-admin-menu span {
    background: #1c1b19;
    display: block;
    height: 2px;
    width: 24px;
}

@media (max-width: 900px) {
    .rbtl-admin-shell {
        grid-template-columns: 1fr;
    }

    .rbtl-admin-sidebar {
        bottom: 0;
        left: 0;
        position: fixed;
        top: 0;
        transform: translateX(-100%);
        transition: transform 0.18s ease;
        width: 260px;
        z-index: 40;
    }

    .rbtl-admin-sidebar.is-open {
        transform: translateX(0);
    }

    .rbtl-admin-menu {
        display: flex;
    }

    .rbtl-admin-topbar {
        align-items: flex-start;
        padding: 18px 20px;
    }

    .rbtl-admin-user {
        align-items: flex-end;
        flex-direction: column;
        gap: 10px;
    }

    .rbtl-admin-content {
        padding: 22px 20px 44px;
    }
}
</style>
