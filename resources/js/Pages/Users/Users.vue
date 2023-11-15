<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Icon from '@/Components/Icon.vue';
import {Head, usePage, Link} from '@inertiajs/vue3';

const users = usePage().props.users;
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Users</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <table class="w-full whitespace-nowrap">
                            <thead>
                            <tr class="text-left font-bold">
                                <th class="pb-4 pt-6 px-6">#</th>
                                <th class="pb-4 pt-6 px-6">Role</th>
                                <th class="pb-4 pt-6 px-6">Email</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                                <td class="border-t pl-6 py-1">
                                    {{ user.id }}
                                </td>
                                <td class="border-t pl-6 py-1">
                                    {{ user.role }}
                                </td>
                                <td class="border-t pl-6 py-1">
                                    {{ user.email }}
                                </td>
                                <td class="border-t">
                                    <Link class="flex items-center px-4" :href="`/admin/users/${user.id}`" tabindex="-1">
                                        <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
                                    </Link>
                                </td>
                            </tr>
                            <tr v-if="users.data.length === 0">
                                <td class="px-6 py-4 border-t" colspan="4">No users yet.</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <pagination class="mt-6" :links="users.links" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
