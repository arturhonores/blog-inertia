<template>
    <Pie :options="chartOptions" :data="chartData" />
</template>

<script setup>
import { Pie } from 'vue-chartjs';
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';
import { defineProps, reactive, watch } from 'vue';

ChartJS.register(ArcElement, Tooltip, Legend);

// Define la prop postsByTag para recibir los datos dinámicos
const props = defineProps({
    postsByTag2024: {
        type: Array,
        required: true
    }
});

// Configura los datos del gráfico de forma reactiva usando los datos de postsByTag
const chartData = reactive({
    labels: props.postsByTag2024.map(item => item.tag),
    datasets: [
        {
            backgroundColor: ['#6366F1', '#1E1B4B', '#1D4ED8', '#D946EF', '#8B5CF6', '#3B82F6', '#9333EA'],
            data: props.postsByTag2024.map(item => item.count)
        }
    ]
});

const chartOptions = reactive({
    responsive: true,
    maintainAspectRatio: false
});

// Observa cambios en postsByTag y actualiza chartData
watch(() => props.postsByTag2024, (newData) => {
    chartData.labels = newData.map(item => item.tag);
    chartData.datasets[0].data = newData.map(item => item.count);
});
</script>
