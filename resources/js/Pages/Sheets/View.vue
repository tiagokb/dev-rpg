<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const currentStep = ref(1);
const totalSteps = 3;

const form = useForm({
    name: '',
    class: '',
    race: '',
    level: 1,
    attributes: {
        strength: 1,
        dexterity: 1,
        constitution: 1,
        intelligence: 1,
        wisdom: 1,
        charisma: 1,
    },
    skills: [{ name: '', level: 1 }],
});

const addSkill = () => {
    form.skills.push({ name: '', level: 1 });
};

const removeSkill = (index) => {
    form.skills.splice(index, 1);
};

const nextStep = () => {
    currentStep.value++;
};

const prevStep = () => {
    currentStep.value--;
};

const submit = () => {
    form.post(route('characters.store'));
};
</script>

<template>
    <form @submit.prevent="submit">
        <!-- Step 1: Basic Info -->
        <div v-if="currentStep === 1">
            <h2>Passo 1: Informações Básicas</h2>
            <label>
                Nome:
                <input v-model="form.name" required>
            </label>
            
            <label>
                Classe:
                <select v-model="form.class" required>
                    <option value="Guerreiro">Guerreiro</option>
                    <option value="Mago">Mago</option>
                    <option value="Ladino">Ladino</option>
                </select>
            </label>

            <label>
                Raça:
                <select v-model="form.race" required>
                    <option value="Humano">Humano</option>
                    <option value="Elfo">Elfo</option>
                    <option value="Anão">Anão</option>
                </select>
            </label>

            <label>
                Nível:
                <input type="number" v-model="form.level" min="1" required>
            </label>
        </div>

        <!-- Step 2: Attributes -->
        <div v-if="currentStep === 2">
            <h2>Passo 2: Atributos</h2>
            <div class="attributes-grid">
                <label v-for="(value, attr) in form.attributes" :key="attr">
                    {{ attr.charAt(0).toUpperCase() + attr.slice(1) }}:
                    <input 
                        type="number" 
                        v-model="form.attributes[attr]" 
                        min="1" 
                        required
                    >
                </label>
            </div>
        </div>

        <!-- Step 3: Skills -->
        <div v-if="currentStep === 3">
            <h2>Passo 3: Habilidades</h2>
            <div v-for="(skill, index) in form.skills" :key="index" class="skill-row">
                <label>
                    Nome:
                    <input v-model="skill.name" required>
                </label>
                <label>
                    Nível:
                    <input type="number" v-model="skill.level" min="1" required>
                </label>
                <button type="button" @click="removeSkill(index)" v-if="form.skills.length > 1">
                    Remover
                </button>
            </div>
            <button type="button" @click="addSkill">Adicionar Habilidade</button>
        </div>

        <!-- Navigation Controls -->
        <div class="navigation">
            <button 
                type="button" 
                @click="prevStep" 
                v-if="currentStep > 1"
            >
                Voltar
            </button>

            <button 
                type="button" 
                @click="nextStep" 
                v-if="currentStep < totalSteps"
            >
                Próximo
            </button>

            <button 
                type="submit" 
                v-if="currentStep === totalSteps"
                :disabled="form.processing"
            >
                Criar Personagem
            </button>
        </div>

        <!-- Progress Indicator -->
        <div class="progress">
            Passo {{ currentStep }} de {{ totalSteps }}
        </div>
    </form>
</template>

<style scoped>
.attributes-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

.skill-row {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.navigation {
    margin-top: 2rem;
    display: flex;
    gap: 1rem;
}

.progress {
    margin-top: 1rem;
    font-weight: bold;
}
</style>