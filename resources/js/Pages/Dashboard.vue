<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage, Link} from '@inertiajs/vue3';

const projects = usePage().props.projects ?? [];
const user = usePage().props.auth.user;
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div v-if="user.role === 'admin'" class="p-6 text-gray-900" >You are logged in</div>
                    <div v-else>
                        Projects
                        <div v-for="project in projects">
                            <Link :href="`/timelog/${project.id}`">
                                {{ project.title }}
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
