<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import Button from '@/Components/Button.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>

            <h2 class="font-rpgSans text-sand-d6 text-2xl">
                Deletar conta
            </h2>

            <p class="mt-1 text-sm text-sand-d6">
                Depois que sua conta for excluída, todos os seus recursos e dados serão excluídos permanentemente. Antes
                de excluir sua conta, baixe todos os dados ou informações que deseja guardar.
            </p>
        </header>

        <Button formato="secondary" size="xs" @click="confirmUserDeletion">Deletar conta</Button>


        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h1 class="font-rpgSans text-sand-d8">
                    Tem certeza de que deseja excluir sua conta?
                </h1>

                <p class="mt-1 text-sm text-sand-d6">
                    Depois que sua conta for excluída, todos os seus recursos e dados serão excluídos permanentemente.
                    Antes de excluir sua conta, baixe todos os dados ou informações que deseja reter.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Password" class="sr-only" />

                    <TextInput id="password" ref="passwordInput" v-model="form.password" type="password"
                        class="mt-1 block w-3/4" placeholder="Senha" @keyup.enter="deleteUser" />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">

                    <Button formato="secondary" size="xs" type="submit" @click="closeModal">Cancelar</Button>

                    <Button formato="secondary" size="xs" class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                        @click="deleteUser">
                        Deletar conta
                    </Button>
                </div>
            </div>
        </Modal>
    </section>
</template>
