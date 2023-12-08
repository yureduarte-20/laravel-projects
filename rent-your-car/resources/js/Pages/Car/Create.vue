<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from "@/Components/Checkbox.vue";
import {Link, useForm} from "@inertiajs/vue3";

const props = defineProps({
    cars: {
        type: Array,
        required: true
    }
})

const form = useForm({
    plate: '',
    model: '',
    brand: '',
    year: new Date().getFullYear(),
    price: {
        price_per_day: 1.00,
        late_price_per_day: 1.00,
    },
    is_available: true
})
const submit = () => {
    form.post(route('admin.car.store'), {
        onError: console.error
    })
}
</script>
<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <form class="p-2" @submit.prevent="submit">
                        <div class="grid-cols-1 md:grid-cols-3 grid gap-1">
                            <div>
                                <InputLabel for="model" value="Modelo"/>
                                <TextInput
                                    id="model"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.model"
                                    required
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.model"/>
                            </div>
                            <div>
                                <InputLabel for="plate" value="Placa"/>
                                <TextInput
                                    id="plate"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.plate"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.plate"/>
                            </div>
                            <div>
                                <InputLabel for="year" value="Ano"/>
                                <TextInput
                                    id="year"
                                    type="number"

                                    class="mt-1 block w-full"
                                    v-model="form.year"
                                    required
                                    autocomplete="current-password"
                                />

                                <InputError class="mt-2" :message="form.errors.year"/>
                            </div>
                        </div>

                        <div class="mt-4 grid-cols-1  md:grid-cols-3 grid gap-1">

                            <div>
                                <InputLabel for="brand" value="Marca"/>
                                <TextInput
                                    id="brand"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.brand"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.brand"/>
                            </div>
                            <div>
                                <InputLabel for="price_per_day" value="Preço por dia"/>
                                <TextInput
                                    id="brand"
                                    type="number"
                                    step="0.01"
                                    min="1.0"
                                    class="mt-1 block w-full"
                                    v-model="form.price.price_per_day"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.price"/>
                            </div>
                            <div>
                                <InputLabel for="late_price_per_day" value="Preço por dia após atraso"/>
                                <TextInput
                                    id="brand"
                                    type="number"
                                    step="0.01"
                                    min="1.0"
                                    class="mt-1 block w-full"
                                    v-model="form.price.late_price_per_day"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.price"/>
                            </div>
                        </div>

                        <div class="block mt-4">
                            <label class="flex items-center">
                                <Checkbox name="is_available" v-model:checked="form.is_available"/>
                                <span class="ms-2 text-sm text-gray-600">Disponível</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }"
                                           :disabled="form.processing">
                                enviar
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
