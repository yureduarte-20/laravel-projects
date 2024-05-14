<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {router} from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    cars: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-3">
                    <table v-if="cars.data.length > 0"
                           class="table-auto w-full bg-white shadow-lg rounded-lg overflow-hidden">
                        <thead class="bg-gray-200 text-black font-bold text-center">
                        <tr>
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Modelo</th>
                            <th class="px-4 py-2">Ano</th>
                            <th class="px-4 py-2">Marca</th>
                            <th class="px-4 py-2">Disponível</th>
                            <th class="px-4 py-2">Placa</th>
                            <th class="px-4 py-2">Criado em</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="cursor-pointer odd:bg-gray-100" v-for="car in cars.data" :key="car.id"
                            @click.prevent="() => router.get(route('admin.car.edit',  {'car' : car.id}  ))">
                            <td class="px-4 py-2 text-center">{{ car.id }}</td>
                            <td class="px-4 py-2">{{ car.model }}</td>
                            <td class="px-4 py-2 text-center">{{ car.year }}</td>
                            <td class="px-4 py-2 text-center">{{ car.brand }}</td>
                            <td class="px-4 py-2 text-center">
                                <span v-if="car.is_available">Sim</span>
                                <span v-else>Não</span>
                            </td>
                            <td class="px-4 py-2 text-center">{{ car.plate }}</td>
                            <td class="px-4 py-2 text-center">{{
                                    new Date(car.created_at).toLocaleString('pt-BR')
                                }}
                            </td>
                        </tr>
                        </tbody>
                        <tfoot class="bg-gray-200 text-black font-bold text-center">
                        <tr>
                            <td colspan="7">
                                <template v-for="link of cars.links">
                                    <SecondaryButton
                                        class="mx-1"
                                        @click.prevent="() => router.get(link.url,{},{
                                            preserveState: true
                                        })"
                                        :disabled="!link.url">
                                        {{ link.label }}
                                    </SecondaryButton>
                                </template>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
