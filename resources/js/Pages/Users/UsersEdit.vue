<script setup>
import { reactive } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {Head, usePage, Link, useForm} from '@inertiajs/vue3';
import Checkbox from "@/Components/Checkbox.vue";

const { user, projects } = usePage().props;
const mappedProjects = reactive(user.projects.map(item => item.id));
const addRemoveProject = id => {
    if (mappedProjects.includes(id)) {
        const index = mappedProjects.findIndex(item => item === id);
        mappedProjects.splice(index, 1);
    } else {
        mappedProjects.push(id);
    }
}

const form = useForm({ mappedProjects });

const saveMappedProjects = () => {
    form.mappedProjects = mappedProjects;
    form.post(`/admin/users/${user.id}`, {
        onFinish: () => {}
    });
}

</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Users</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    Email: <div class="font-bold">{{ user.email }}</div>

                    <br/>
                    Projects:
                    <div v-for="project in projects" class="flex items-center">
                        <checkbox
                            :checked="mappedProjects.includes(project.id)"
                            @change="() => addRemoveProject(project.id)"
                        />
                        <div class="ml-4">
                            {{ project.title }}
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton class="ms-4"
                                       @click="saveMappedProjects"
                                       :class="{ 'opacity-25': form.processing }"
                                       :disabled="form.processing">
                            Save
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
