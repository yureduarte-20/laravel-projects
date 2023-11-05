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

</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Barbeiro</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <form @submit.prevent="()=>{}" class="mt-6 space-y-6">
                        <div class="w-full flex gap-2">
                            <div class="w-3/4">
                                <InputLabel class="w-full"  value="Nome"/>
                                <TextInput class="w-full" disabled required v-model="props.barber.name"/>

                            </div>
                            <div class="w-1/4">
                                <InputLabel class="w-full" value="Salário"/>
                                <TextInput class="w-full" disabled type="number" required step="0.01" min="0.00"
                                           v-model="props.barber.salary"/>
                            </div>
                        </div>
                        <div class="w-full flex gap-2">
                            <div class="w-2/4">
                                <InputLabel class="w-full" value="email"/>
                                <TextInput class="w-full" disabled type="email"  step="0.01" min="0.00"
                                           v-model="props.barber.email"/>
                            </div>
                            <div class="w-2/4">
                                <InputLabel class="w-full" value="Agendamentos Cadastrados"/>
                                <TextInput class="w-full" type="email" disabled step="0.01" min="0.00"
                                           v-model="props.barber.appointments"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
