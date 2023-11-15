<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import {usePage, Link, useForm, router} from "@inertiajs/vue3";
import '@vuepic/vue-datepicker/dist/main.css'

const { log, projectId } = usePage().props;
const errorMessage = ref('');

const form = useForm({
    startTime: {
        hours: new Date(log.started_at).getHours(),
        minutes: new Date(log.started_at).getMinutes(),
        seconds: new Date(log.started_at).getSeconds(),
    },
    finishTime: {
        hours: new Date(log.finished_at).getHours(),
        minutes: new Date(log.finished_at).getMinutes(),
        seconds: new Date(log.finished_at).getSeconds(),
    }
});

const save = () => {
    form.post(`/timelog/${projectId}/history/${log.id}`, {
        onFinish: () => {
            // show error or redirect
        }
    });
};

const remove = () => {
    router.delete(`/timelog/${projectId}/history/${log.id}`);
}

</script>

<template>
    <Head title="Time Log Edit" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Time Log Edit</h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg px-6 py-4 min-h-[300px]">
                    <div class="mt-6">
                        <div class="flex">
                            <div class="flex flex-1 items-center">
                                <div class="pr-6">From time: </div>
                                <div>
                                    <VueDatePicker v-model="form.startTime" time-picker enable-seconds teleport-center></VueDatePicker>
                                </div>
                            </div>

                            <div class="flex flex-1 items-center">
                                <div class="pr-6">To time: </div>
                                <div>
                                    <VueDatePicker v-model="form.finishTime" time-picker enable-seconds teleport-center></VueDatePicker>
                                </div>
                            </div>
                        </div>
                        <div>
                            <InputError class="mt-2" :message="errorMessage" />
                        </div>

                        <div class="flex justify-end w-full">
                            <PrimaryButton class="ms-4" @click="remove">
                                Delete
                            </PrimaryButton>
                            <PrimaryButton class="ms-4" @click="save">
                                Save
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
