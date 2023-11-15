<script setup>
import { reactive } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {usePage, Link} from "@inertiajs/vue3";
import { Chart } from 'highcharts-vue'

const projectId = usePage().props.projectId;

const now = new Date();
const chartOptions = reactive({
    xAxis: {
        type: "datetime",
        title: {
            text: 'Monthly Dates'
        },
    },
    series: [
        {
            name: 'Total Confirmed',
            type: 'line',
            data: [19.9, 31.5, 25.4, 75, 45, 27, 12, 15, 19, 20],
            pointStart: Date.UTC(now.getFullYear(), now.getMonth(), 1),
            pointInterval: 24 * 3600 * 1000 // one day
        }
    ]
});

</script>

<template>
    <Head title="Time Log" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center">
                <Link :href="`/timelog/${projectId}`" class="w-[120px]">
                    Time Log
                </Link>
                <Link :href="`/timelog/${projectId}/history`" class="w-[120px]">
                    Logs History
                </Link>
                <h2 class="font-semibold text-gray-800 leading-tight w-[120px]">Logs Statistics</h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <Chart :options="chartOptions"></Chart>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
