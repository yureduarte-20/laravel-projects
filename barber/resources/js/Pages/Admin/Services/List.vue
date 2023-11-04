<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TableService from "@/Pages/Admin/Services/Components/TableService.vue";
import {router} from "@inertiajs/vue3";


const props = defineProps({
    services: {
        required: true,
        type: Array
    }
})
const handleDelete = id => {
    if(!window.confirm("Tem certeza? essa operação não pode ser desfeita")) return
    router.delete(route('barberService.destroy', {
        barberService: id
    }), {
        onError: params => {
            window.alert('Error', JSON.stringify(params) )
        }
    })
}
const handleEdit = id =>  router.get(route('barberService.edit', id))
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Serviços da Barbearia</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-2">
                        <TableService :services="services" @deleteRequest="handleDelete" @edit-request="handleEdit"/>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
