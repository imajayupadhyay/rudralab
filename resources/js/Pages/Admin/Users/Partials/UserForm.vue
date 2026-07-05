<script setup>
import { computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    adminUser: {
        type: Object,
        required: true,
    },
    mode: {
        type: String,
        required: true,
    },
});

const isEdit = computed(() => props.mode === 'edit');

const form = useForm({
    name: props.adminUser.name || '',
    email: props.adminUser.email || '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    if (isEdit.value) {
        form
            .transform((data) => ({
                ...data,
                _method: 'put',
            }))
            .post(`/rbtl/users/${props.adminUser.id}`);
        return;
    }

    form.post('/rbtl/users');
};
</script>

<template>
    <form class="rbtl-admin-card rbtl-user-form" @submit.prevent="submit">
        <div class="rbtl-user-form-head">
            <div>
                <span>{{ isEdit ? 'Update' : 'Create' }}</span>
                <h2>{{ isEdit ? adminUser.name : 'New admin account' }}</h2>
                <p v-if="isEdit">
                    Created {{ adminUser.created_at || '-' }}. Verified {{ adminUser.email_verified_at || '-' }}.
                </p>
            </div>
            <div class="rbtl-user-form-actions">
                <Link href="/rbtl/users">Cancel</Link>
                <button type="submit" :disabled="form.processing">
                    {{ form.processing ? 'Saving...' : 'Save Admin' }}
                </button>
            </div>
        </div>

        <div class="rbtl-user-form-grid">
            <label>
                <span>Name</span>
                <input v-model="form.name" type="text" autocomplete="name" />
                <small v-if="form.errors.name">{{ form.errors.name }}</small>
            </label>

            <label>
                <span>Email</span>
                <input v-model="form.email" type="email" autocomplete="username" />
                <small v-if="form.errors.email">{{ form.errors.email }}</small>
            </label>

            <label>
                <span>{{ isEdit ? 'New Password' : 'Password' }}</span>
                <input v-model="form.password" type="password" autocomplete="new-password" />
                <small v-if="form.errors.password">{{ form.errors.password }}</small>
            </label>

            <label>
                <span>Confirm Password</span>
                <input v-model="form.password_confirmation" type="password" autocomplete="new-password" />
            </label>
        </div>

        <p class="rbtl-user-note">
            Every account created here is an admin account. Leave password blank while editing to keep the current password.
        </p>
    </form>
</template>

<style>
.rbtl-user-form {
    padding: 24px;
}

.rbtl-user-form-head {
    align-items: center;
    display: flex;
    gap: 18px;
    justify-content: space-between;
    margin-bottom: 24px;
}

.rbtl-user-form-head span,
.rbtl-user-form label > span {
    color: #6b6862;
    display: block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
}

.rbtl-user-form-head h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 32px;
    line-height: 1;
    margin: 6px 0 0;
}

.rbtl-user-form-head p,
.rbtl-user-note {
    color: #6b6862;
    font-size: 13.5px;
    line-height: 1.6;
    margin: 10px 0 0;
}

.rbtl-user-form-actions {
    align-items: center;
    display: flex;
    gap: 12px;
}

.rbtl-user-form-actions a {
    color: #234a3e;
    font-size: 13px;
    font-weight: 800;
    text-decoration: none;
}

.rbtl-user-form-actions button {
    background: #234a3e;
    border: none;
    border-radius: 8px;
    color: #f6f4ef;
    cursor: pointer;
    font: inherit;
    font-size: 13px;
    font-weight: 800;
    padding: 12px 16px;
}

.rbtl-user-form-actions button:disabled {
    cursor: not-allowed;
    opacity: 0.72;
}

.rbtl-user-form-grid {
    display: grid;
    gap: 18px;
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.rbtl-user-form label {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.rbtl-user-form input {
    background: #fbfaf7;
    border: 1px solid rgba(28, 27, 25, 0.14);
    border-radius: 8px;
    color: #1c1b19;
    font: inherit;
    padding: 12px 14px;
}

.rbtl-user-form input:focus {
    border-color: #234a3e;
    box-shadow: 0 0 0 3px rgba(35, 74, 62, 0.12);
    outline: none;
}

.rbtl-user-form small {
    color: #b24a3e;
    font-size: 12.5px;
}

@media (max-width: 760px) {
    .rbtl-user-form-head,
    .rbtl-user-form-actions {
        align-items: flex-start;
        flex-direction: column;
    }

    .rbtl-user-form-grid {
        grid-template-columns: 1fr;
    }
}
</style>
