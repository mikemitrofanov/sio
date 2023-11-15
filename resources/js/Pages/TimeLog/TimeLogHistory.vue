<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import Icon from '@/Components/Icon.vue';
import {usePage, Link} from "@inertiajs/vue3";

const { logs, projectId } = usePage().props;
</script>

<template>
    <Head title="Time Log History" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center">
                <Link :href="`/timelog/${projectId}`" class="w-[120px]">
                    Time Log
                </Link>
                <h2 class="font-semibold text-gray-800 leading-tight w-[120px]">Logs History</h2>
                <Link :href="`/timelog/${projectId}/statistics`" class="w-[120px]">
                    Logs Statistics
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg px-6 py-4 min-h-[300px]">
                    <table class="w-full whitespace-nowrap">
                        <thead>
                        <tr class="text-left font-bold">
                            <th class="pb-4 pt-6 px-6">Start date</th>
                            <th class="pb-4 pt-6 px-6">End date</th>
                            <th class="pb-4 pt-6 px-6 max-w-[40px]">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="log in logs.data" :key="log.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                            <td class="border-t">
                                {{ log.started_at }}
                            </td>
                            <td class="border-t">
                                {{ log.finished_at }}
                            </td>
                            <td class="border-t max-w-[40px]">

                                <Link class="flex items-center px-4" :href="`/timelog/${projectId}/history/${log.id}`" tabindex="-1">
                                    <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="logs.data.length === 0">
                            <td class="px-6 py-4 border-t" colspan="4">No time logs yet.</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <pagination class="mt-6" :links="logs.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
