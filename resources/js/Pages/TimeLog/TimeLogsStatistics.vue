<script setup>
import { reactive } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {usePage, Link} from "@inertiajs/vue3";
import { Chart } from 'highcharts-vue'

const { logs, projectId } = usePage().props;
const totalDays = new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0).getDate();
const values = Array.from({ length: totalDays }, (_) => 0);
logs.forEach(item => {
    const index = parseInt(item.day, 10) - 1;
    values[index] = parseInt(item.total_time / 3600);
})

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
            name: 'Tracked per day',
            type: 'column',
            data: values,
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
