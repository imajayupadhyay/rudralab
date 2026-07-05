<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/rbtl-secure-login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="RBTL Admin Login" />

    <main class="rbtl-login-page">
        <section class="rbtl-login-panel">
            <div class="rbtl-login-brand">
                <span>R</span>
                <div>
                    <strong>RBTL</strong>
                    <small>Secure Admin</small>
                </div>
            </div>

            <h1>Admin Login</h1>
            <p>Access the RBTL control panel to manage certificates and site content.</p>

            <form class="rbtl-login-form" @submit.prevent="submit">
                <label>
                    <span>Email</span>
                    <input v-model="form.email" type="email" autocomplete="username" autofocus />
                    <small v-if="form.errors.email">{{ form.errors.email }}</small>
                </label>

                <label>
                    <span>Password</span>
                    <input v-model="form.password" type="password" autocomplete="current-password" />
                    <small v-if="form.errors.password">{{ form.errors.password }}</small>
                </label>

                <label class="rbtl-login-check">
                    <input v-model="form.remember" type="checkbox" />
                    <span>Remember this session</span>
                </label>

                <button type="submit" :disabled="form.processing">
                    {{ form.processing ? 'Signing in...' : 'Sign in' }}
                </button>
            </form>
        </section>
    </main>
</template>

<style>
body {
    margin: 0;
}

.rbtl-login-page {
    align-items: center;
    background: #f6f4ef;
    color: #1c1b19;
    display: grid;
    font-family: 'Manrope', sans-serif;
    min-height: 100vh;
    padding: 32px;
}

.rbtl-login-panel {
    background: #ffffff;
    border: 1px solid rgba(28, 27, 25, 0.1);
    border-radius: 8px;
    box-shadow: 0 28px 70px rgba(35, 74, 62, 0.1);
    margin: 0 auto;
    max-width: 430px;
    padding: 38px;
    width: 100%;
}

.rbtl-login-brand {
    align-items: center;
    display: flex;
    gap: 12px;
    margin-bottom: 34px;
}

.rbtl-login-brand > span {
    border: 1px solid #234a3e;
    border-radius: 50%;
    color: #234a3e;
    display: grid;
    font-family: 'Cormorant Garamond', serif;
    font-size: 24px;
    font-weight: 700;
    height: 44px;
    place-items: center;
    width: 44px;
}

.rbtl-login-brand strong {
    display: block;
    font-family: 'Cormorant Garamond', serif;
    font-size: 26px;
    line-height: 1;
}

.rbtl-login-brand small {
    color: #6b6862;
    display: block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.18em;
    margin-top: 4px;
    text-transform: uppercase;
}

.rbtl-login-panel h1 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 42px;
    line-height: 1;
    margin: 0 0 12px;
}

.rbtl-login-panel p {
    color: #6b6862;
    font-size: 14.5px;
    line-height: 1.6;
    margin: 0 0 28px;
}

.rbtl-login-form {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.rbtl-login-form label {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.rbtl-login-form label > span {
    color: #6b6862;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.12em;
    text-transform: uppercase;
}

.rbtl-login-form input[type='email'],
.rbtl-login-form input[type='password'] {
    background: #fbfaf7;
    border: 1px solid rgba(28, 27, 25, 0.16);
    border-radius: 8px;
    color: #1c1b19;
    font: inherit;
    padding: 14px 15px;
}

.rbtl-login-form input:focus {
    border-color: #234a3e;
    box-shadow: 0 0 0 3px rgba(35, 74, 62, 0.12);
    outline: none;
}

.rbtl-login-form small {
    color: #b24a3e;
    font-size: 12.5px;
}

.rbtl-login-check {
    align-items: center;
    flex-direction: row !important;
    gap: 10px !important;
}

.rbtl-login-check span {
    color: #4a4842 !important;
    font-size: 13px !important;
    letter-spacing: 0 !important;
    text-transform: none !important;
}

.rbtl-login-form button {
    background: #234a3e;
    border: none;
    border-radius: 8px;
    color: #f6f4ef;
    cursor: pointer;
    font: inherit;
    font-weight: 800;
    margin-top: 6px;
    padding: 15px 18px;
}

.rbtl-login-form button:disabled {
    cursor: not-allowed;
    opacity: 0.72;
}
</style>
