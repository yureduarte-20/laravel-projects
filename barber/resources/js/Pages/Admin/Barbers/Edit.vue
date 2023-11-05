<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {router, useForm} from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
const props = defineProps({
    barber: {
        required: true,
        type: Object
    }
})
const formSubmit = useForm({
    name: props.barber.name,
    salary: props.barber.salary,
    password: '',
    confirmPassword: ''
})
const handleSubimit = () => {
    formSubmit.put(route('barber.update', props.barber.id), {
        onSuccess: () => console.log('Criado sem problemas')
    })
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Barbeiro</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <form @submit.prevent="handleSubimit" class="mt-6 space-y-6">
                        <div class="w-full flex gap-2">
                            <div class="w-3/4">
                                <InputLabel class="w-full"  value="Nome"/>
                                <TextInput class="w-full" required v-model="formSubmit.name"/>
                                <InputError v-if="formSubmit.errors.name" :message="formSubmit.errors.name" />
                            </div>
                            <div class="w-1/4">
                                <InputLabel class="w-full" value="Salário"/>
                                <TextInput class="w-full" type="number" required step="0.01" min="0.00"
                                           v-model="formSubmit.salary"/>
                                <InputError v-if="formSubmit.errors.salary" :message="formSubmit.errors.salary" />
                            </div>
                        </div>
                        <div class="w-full flex gap-2">
                            <div class="w-2/4">
                                <InputLabel class="w-full" value="email - Não pode ser alterado"/>
                                <TextInput class="w-full" type="email" disabled step="0.01" min="0.00"
                                           v-model="props.barber.email"/>
                            </div>
                            <div class="w-1/4">
                                <InputLabel class="w-full" value="Senha"/>
                                <TextInput class="w-full" type="password"
                                           v-model="formSubmit.password"/>
                                <InputError v-if="formSubmit.errors.password" :message="formSubmit.errors.password" />
                            </div>
                            <div class="w-1/4">
                                <InputLabel class="w-full" value="Confirmar Senha"/>
                                <TextInput class="w-full" type="password"
                                           v-model="formSubmit.confirmPassword"/>
                                <InputError v-if="formSubmit.errors.confirmPassword" :message="formSubmit.errors.confirmPassword" />
                            </div>
                        </div>
                        <PrimaryButton type="submit">Enviar</PrimaryButton>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
