<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from "@/Components/Checkbox.vue";
import {Link, router, useForm} from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import { ref } from "vue";
const confirmingUserDeletion = ref(false);

const closeModal = () => confirmingUserDeletion.value = false
const props = defineProps({
    car: {
        type: Object,
        required: true
    }
})

const deleteCar = () =>{
    router.delete(route('admin.car.destroy', props.car.id), {
        onSuccess: () => console.log('deletado com sucesso'),
        onError: console.error
     })
}

const form = useForm({
    plate: props.car.plate,
    model: props.car.model,
    brand: props.car.brand,
    year: props.car.year,
    price: {
        price_per_day: props.car.price.price_per_day,
        late_price_per_day: props.car.price.late_price_per_day,
    },
    is_available: props.car.is_available === 1
})
const submit = () => {
    form.patch(route('admin.car.update', { 'car' : props.car.id }), {
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
                                    disabled
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
                            <DangerButton @click="confirmingUserDeletion = true" class="ms-4" :class="{ 'opacity-25': form.processing }"
                                        >
                            Deletar
                            </DangerButton>
                            <PrimaryButton type="submit" class="ms-4" :class="{ 'opacity-25': form.processing }"
                                           :disabled="form.processing">
                                Atualizar
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                   Você tem certeza que deseja apagar este carro?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Todos os emprestimos associados a ele também serão excluídos
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteCar"
                    >
                        Apagar carro
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
