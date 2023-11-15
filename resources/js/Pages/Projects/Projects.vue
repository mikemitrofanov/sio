<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {Head, usePage, Link} from '@inertiajs/vue3';

const projects = usePage().props.projects;
</script>

<template>
    <Head title="Projects" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Projects</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="flex items-center justify-between mb-6">
                    <primary-button>
                        <Link :href="route('admin.createProject')">
                            <span>Create</span>
                            <span class="hidden md:inline">&nbsp;Project</span>
                        </Link>
                    </primary-button>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <table class="w-full whitespace-nowrap">
                        <thead>
                        <tr class="text-left font-bold">
                            <th class="pb-4 pt-6 px-6">#</th>
                            <th class="pb-4 pt-6 px-6">Title</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="project in projects.data" :key="project.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                            <td class="border-t pl-6 py-1">
                                {{ project.id }}
                            </td>
                            <td class="border-t pl-6 py-1">
                                {{ project.title }}
                            </td>
                        </tr>
                        <tr v-if="projects.data.length === 0">
                            <td class="px-6 py-4 border-t" colspan="4">No projects yet.</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <pagination class="mt-6" :links="projects.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
