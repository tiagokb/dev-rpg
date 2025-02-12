<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },

});
</script>

<template>
    <Head :title=$page.props.auth.user.name />

    <AuthenticatedLayout>

            <div class="container mx-auto grid grid-cols-2 gap-4 pb-8">
                <div class="col-span-1 bg-charcoal-d12 rounded-lg p-8">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                    />
                </div>

                <div class="col-span-1 bg-charcoal-d12 rounded-lg p-8">
                    <UpdatePasswordForm />
                </div>

                <div class="col-span-2 bg-charcoal-d12 rounded-lg p-8">
                    <DeleteUserForm />
                </div>
            </div>
    </AuthenticatedLayout>
</template>
