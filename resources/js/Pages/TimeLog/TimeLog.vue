<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import {usePage} from "@inertiajs/vue3";
import '@vuepic/vue-datepicker/dist/main.css'

const projectId = usePage().props.projectId;
const lastUnfinishedLog = ref(usePage().props.lastUnfinishedLog);
const toggle = ref(true);
const timeString = ref('');
const startTime = ref(null);
const finishTime = ref(null);
const errorMessage = ref(null);

const startTracking = () => {

}

const stopTracking = () => {

}

const submitManualTime = () => {

}
</script>

<template>
    <Head title="Time Log" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Time Log</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg px-6 py-4 min-h-[300px]">
                    <label for="toggle" class="flex items-center cursor-pointer relative mb-4">
                        <input type="checkbox" id="toggle" class="sr-only" v-model="toggle" :disabled="lastUnfinishedLog !== null">
                        <div class="toggle-bg bg-gray-200 border-2 border-gray-200 h-6 w-11 rounded-full"></div>
                        <span class="ml-3 text-gray-900 text-sm font-medium">Auto tracking</span>
                    </label>

                    <div v-if="toggle" class="justify-center w-full flex">
                        <div>
                            <div v-if="lastUnfinishedLog && timeString" class="text-center mb-2 font-bold text-3xl">
                                {{ timeString }}
                            </div>

                            <button
                                v-if="!lastUnfinishedLog"
                                @click="startTracking"
                                class="flex shadow w-32 h-32 block border-green-600 border-2 rounded-full
                                focus:outline-none px-4 py-2 text-green-600 hover:bg-green-600
                                hover:text-white justify-center items-center"
                            >
                                Start
                            </button>
                            <Button v-else
                                    @click="stopTracking"
                                    class="flex shadow w-32 h-32 block border-red-600 border-2 rounded-full
                                focus:outline-none px-4 py-2 text-red-600 hover:bg-red-600
                                hover:text-white justify-center items-center"
                            >
                                Stop
                            </Button>
                        </div>
                    </div>
                    <div v-else class="mt-6">
                        <div class="flex">
                            <div class="flex flex-1 items-center">
                                <div class="pr-6">From time: </div>
                                <div>
                                    <VueDatePicker v-model="startTime" time-picker teleport-center></VueDatePicker>
                                </div>
                            </div>

                            <div class="flex flex-1 items-center">
                                <div class="pr-6">To time: </div>
                                <div>
                                    <VueDatePicker v-model="finishTime" time-picker teleport-center></VueDatePicker>
                                </div>
                            </div>
                        </div>
                        <div>
                            <InputError class="mt-2" :message="errorMessage" />
                        </div>
                        <div class="flex justify-end w-full">
                            <PrimaryButton class="ms-4" @click="submitManualTime">
                                Submit
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
input:checked:disabled + .toggle-bg {
    background-color: gray;
    border-color: gray;
}
</style>
