<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    submission: {
        type: Object,
        required: true,
    },
});

const destroySubmission = () => {
    if (!window.confirm(`Delete message from ${props.submission.name}?`)) {
        return;
    }

    router.delete(`/rbtl/contact-submissions/${props.submission.id}`);
};
</script>

<template>
    <Head title="Contact Submission" />

    <AdminLayout title="Contact Message" eyebrow="Form Lead">
        <section class="rbtl-admin-card rbtl-submission-show">
            <div class="rbtl-submission-head">
                <div>
                    <span>Received {{ submission.created_at }}</span>
                    <h2>{{ submission.name }}</h2>
                </div>
                <div>
                    <Link href="/rbtl/contact-submissions">Back</Link>
                    <button type="button" @click="destroySubmission">Delete</button>
                </div>
            </div>

            <div class="rbtl-submission-grid">
                <div>
                    <span>Status</span>
                    <strong>{{ submission.status }}</strong>
                </div>
                <div>
                    <span>Phone</span>
                    <strong>{{ submission.phone || '-' }}</strong>
                </div>
                <div>
                    <span>Email</span>
                    <strong>{{ submission.email || '-' }}</strong>
                </div>
                <div>
                    <span>Read At</span>
                    <strong>{{ submission.read_at || '-' }}</strong>
                </div>
                <div>
                    <span>IP Address</span>
                    <strong>{{ submission.ip_address || '-' }}</strong>
                </div>
            </div>

            <div class="rbtl-message-box">
                <span>Message</span>
                <p>{{ submission.message }}</p>
            </div>

            <div class="rbtl-user-agent">
                <span>User Agent</span>
                <p>{{ submission.user_agent || '-' }}</p>
            </div>
        </section>
    </AdminLayout>
</template>

<style>
.rbtl-submission-show {
    padding: 24px;
}

.rbtl-submission-head {
    align-items: center;
    display: flex;
    gap: 18px;
    justify-content: space-between;
    margin-bottom: 24px;
}

.rbtl-submission-head span,
.rbtl-submission-grid span,
.rbtl-message-box span,
.rbtl-user-agent span {
    color: #6b6862;
    display: block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
}

.rbtl-submission-head h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 34px;
    line-height: 1;
    margin: 6px 0 0;
}

.rbtl-submission-head div:last-child {
    align-items: center;
    display: flex;
    gap: 12px;
}

.rbtl-submission-head a {
    color: #234a3e;
    font-size: 13px;
    font-weight: 800;
    text-decoration: none;
}

.rbtl-submission-head button {
    background: #b24a3e;
    border: none;
    border-radius: 8px;
    color: #ffffff;
    cursor: pointer;
    font: inherit;
    font-size: 13px;
    font-weight: 800;
    padding: 11px 15px;
}

.rbtl-submission-grid {
    display: grid;
    gap: 14px;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    margin-bottom: 22px;
}

.rbtl-submission-grid div,
.rbtl-message-box,
.rbtl-user-agent {
    background: #fbfaf7;
    border: 1px solid rgba(28, 27, 25, 0.08);
    border-radius: 8px;
    padding: 16px;
}

.rbtl-submission-grid strong {
    display: block;
    font-size: 15px;
    margin-top: 7px;
    word-break: break-word;
}

.rbtl-message-box {
    margin-bottom: 16px;
}

.rbtl-message-box p,
.rbtl-user-agent p {
    color: #1c1b19;
    font-size: 15px;
    line-height: 1.7;
    margin: 10px 0 0;
    white-space: pre-line;
}

.rbtl-user-agent p {
    color: #6b6862;
    font-size: 13px;
    word-break: break-word;
}

@media (max-width: 820px) {
    .rbtl-submission-head {
        align-items: flex-start;
        flex-direction: column;
    }

    .rbtl-submission-grid {
        grid-template-columns: 1fr;
    }
}
</style>
