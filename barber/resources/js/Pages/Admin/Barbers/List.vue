<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TableService from "@/Pages/Admin/Services/Components/TableService.vue";
import {router} from "@inertiajs/vue3";
import TableBarbers from "@/Pages/Admin/Barbers/Components/TableBarbers.vue";


const props = defineProps({
    barbers: {
        required: true,
        type: Array
    }
})
const handleDelete = id => {
    if(!window.confirm("Tem certeza? essa operação não pode ser desfeita")) return
    router.delete(route('barber.destroy', {
        barber: id
    }), {
        onError: params => {
            window.alert('Error', JSON.stringify(params) )
        }
    })
}
const handleEdit = id =>  router.get(route('barber.edit', id))
const handleShow = id =>  router.get(route('barber.show', id))
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Barbeiros</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-2">
                        <TableBarbers :barbers="barbers"
                                      @view-request="handleShow"
                                      @deleteRequest="handleDelete"
                                      @edit-request="handleEdit"/>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
