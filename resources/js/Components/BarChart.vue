<template>
    <Bar id="my-chart-id" :options="chartOptions" :data="chartData" />
</template>

<script setup>
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';
import { reactive, watch } from 'vue';

// Registrar componentes de Chart.js
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

// Usar defineProps en la sintaxis de <script setup>
const props = defineProps({
    postsByYear: {
        type: Array,
        required: true
    }
});

// Verifica los datos en postsByYear
console.log("Datos en props.postsByYear:", props.postsByYear);

const chartData = reactive({
    labels: props.postsByYear.map(item => item.year),
    datasets: [
        {
            label: 'Número de Posts',
            backgroundColor: ['#6366F1'],
            data: props.postsByYear.map(item => item.count)
        }
    ]
});

const chartOptions = reactive({
    responsive: true,
    maintainAspectRatio: false
});

// Observa cambios en postsByYear y actualiza chartData
watch(() => props.postsByYear, (newData) => {
    chartData.labels = newData.map(item => item.year);
    chartData.datasets[0].data = newData.map(item => item.count);
});
</script>
