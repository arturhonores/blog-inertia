<template>
    <Pie :options="chartOptions" :data="chartData" />
</template>

<script>
import { Pie } from 'vue-chartjs'
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js'

ChartJS.register(ArcElement, Tooltip, Legend)

export default {
    name: 'PieChart',
    components: { Pie },
    data() {
        return {
            chartData: {
                labels: ['Convocatorias', 'Exp. FAP', 'Arraigo', 'Sueldos', 'Salidas', 'Requisitos', 'Funciones'],
                datasets: [
                    {
                        backgroundColor: ['#6366F1', '#1E1B4B', '#1D4ED8', '#D946EF'],
                        data: [60, 50, 32, 20]
                    }
                ]
            },
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false
            }
        }
    }
}
</script>