<script setup>
import {onMounted, ref, reactive} from 'vue';
import moment from 'moment';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import {usePage, Link} from "@inertiajs/vue3";
import '@vuepic/vue-datepicker/dist/main.css'

const projectId = usePage().props.projectId;
const lastUnfinishedLog = ref(usePage().props.lastUnfinishedLog);
const toggle = ref(true);
const timeString = ref('');
const startTime = ref(null);
const finishTime = ref(null);
const errorMessage = ref(null);

let token = null;
let interval = null;

onMounted(() => {
    token = document.querySelectorAll('meta[name="csrf-token"]')[0].getAttribute('content');
    // if user hasn't finished previous tracking, show time and "Stop' button
    if (lastUnfinishedLog.value) {
        interval = setInterval(calculateTrackingTime, 1000);
    }
});

const calculateTrackingTime = () => {
    const diff = moment().format('X') - moment(lastUnfinishedLog.value.started_at).format("X");
    const duration = moment.duration(diff, 'seconds');
    timeString.value = moment.utc(duration.as('milliseconds')).format('HH:mm:ss');
}


const startTracking = async () => {
    // set token as interceptor for fetch api requests
    const response = await fetch(`/timelog/${projectId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token
        }
    });
    const object = await response.json();
    lastUnfinishedLog.value = {
        ...lastUnfinishedLog.value,
        id: object.lastUnfinishedLogId,
        started_at: new Date()
    };
    interval = setInterval(calculateTrackingTime, 1000);
}

const stopTracking = async () => {
    await fetch(`/timelog/${lastUnfinishedLog.value.id}/stop`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token
        }
    });
    lastUnfinishedLog.value = null;
    timeString.value = '';
    clearInterval(interval);
}

const submitManualTime = async () => {
    if (!startTime.value || !finishTime.value) {
        errorMessage.value = 'Select a range of time to track';
        return;
    }

    const formData = new FormData();
    formData.append('startTime', JSON.stringify(startTime.value));
    formData.append('finishTime', JSON.stringify(finishTime.value));

    const response = await fetch(`/timelog/${projectId}/manual`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token
        },
        body: formData
    });

    if (!response.ok) errorMessage.value = await response.text();
    else window.location = `/timelog/${projectId}/history`;
}
</script>

<template>
    <Head title="Time Log" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center">
                <h2 class="font-semibold text-gray-800 leading-tight w-[120px]">Time Log</h2>
                <Link :href="`/timelog/${projectId}/history`" class="w-[120px]">
                    Logs History
                </Link>
                <Link :href="`/timelog/${projectId}/statistics`" class="w-[120px]">
                    Logs Statistics
                </Link>
            </div>
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
                                    <VueDatePicker v-model="startTime" time-picker enable-seconds teleport-center></VueDatePicker>
                                </div>
                            </div>

                            <div class="flex flex-1 items-center">
                                <div class="pr-6">To time: </div>
                                <div>
                                    <VueDatePicker v-model="finishTime" time-picker enable-seconds teleport-center></VueDatePicker>
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
