<template>
    <Pie :options="chartOptions" :data="chartData" />
</template>

<script setup>
import { Pie } from 'vue-chartjs';
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';
import { defineProps, reactive, watch } from 'vue';

ChartJS.register(ArcElement, Tooltip, Legend);

// Define las props para recibir los datos dinámicos
const props = defineProps({
    postsByCategory2024: {
        type: Array,
        required: true
    }
});

// Configura los datos del gráfico con reactividad
const chartData = reactive({
    labels: props.postsByCategory2024.map(item => item.category),
    datasets: [
        {
            backgroundColor: ['#6366F1', '#1E1B4B', '#1D4ED8', '#D946EF'],
            data: props.postsByCategory2024.map(item => item.count)
        }
    ]
});

const chartOptions = reactive({
    responsive: true,
    maintainAspectRatio: false
});

// Observa cambios en postsByCategory2024 y actualiza chartData
watch(() => props.postsByCategory2024, (newData) => {
    chartData.labels = newData.map(item => item.category);
    chartData.datasets[0].data = newData.map(item => item.count);
});
</script>
