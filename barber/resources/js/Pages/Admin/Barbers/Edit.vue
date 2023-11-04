<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {useForm} from "@inertiajs/vue3";
const props = defineProps({
    service: {
        type: Object,
        required: true
    }
})
const formSubmit = useForm({
    price: props.service.price,
    description: props.service.description,
    name: props.service.name
})
const handleSubimit = () =>{
    formSubmit.put(route('barberService.update', props.service.id), {
        onError: console.error
    })
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Serviço "{{service.name}}"</h2>
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
                                <InputLabel class="w-full" value="Preço"/>
                                <TextInput class="w-full" type="number" required step="0.01" min="0.00"
                                           v-model="formSubmit.price"/>
                                <InputError v-if="formSubmit.errors.price" :message="formSubmit.errors.price" />
                            </div>
                        </div>
                        <InputLabel class="w-full" value="Descrição"/>
                        <textarea
                            @input="formSubmit.description = $event.target.value"
                            v-model="formSubmit.description" required class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                        <InputError v-if="formSubmit.errors.price"  :message="formSubmit.errors.description" />
                        <PrimaryButton type="submit">Enviar</PrimaryButton>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
