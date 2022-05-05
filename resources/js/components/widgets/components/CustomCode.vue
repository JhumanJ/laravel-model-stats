<template>
    <div class="daily-count relative max-h-full max-w-full">
        <div class="bg-white border shadow-sm max-h-full max-w-full h-full">
            <div class="p-5 pb-12 h-full max-h-full max-w-full flex flex-col group overflow-scroll">
                <div class="graph-title">
                    <h3 class="font-medium text-xl truncate">{{ widget.title }}</h3>
                </div>
                <template v-if="loadedData && !loading">
                    <line-chart v-if="widget.chart_type === 'line_chart'" class="relative max-h-full flex-grow"
                                :options="chartOptions"
                                :chart-data="chartData"/>
                    <bar-chart v-if="widget.chart_type === 'bar_chart'" class="relative max-h-full flex-grow"
                               :options="chartOptions"
                               :chart-data="chartData"/>
                </template>
                <div class="flex items-center justify-center flex-grow" v-else-if="loading">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 animate-spin" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                              clip-rule="evenodd"/>
                    </svg>
                </div>
                <button
                    class="opacity-0 group-hover:opacity-100 absolute top-3 right-3 p-2 rounded-full bg-white hover:bg-gray-50 cursor-pointer"
                    @click.prevent="deleteChart"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                              clip-rule="evenodd"/>
                    </svg>
                </button>
                <p @click="showCode=!showCode"
                   class="text-xs text-center font-light text-gray-400 cursor-pointer">
                    Custom Code
                </p>
                <div class="w-full" v-if="showCode">
                    <pre class="mt-4 p-4 flex border bg-blue-100 w-full overflow-x-scroll select-all" v-if="showCode">{{widget.code}}</pre>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import axios from "axios";
import {mapState} from "vuex";
import LineChart from "../../charts/LineChart";
import BarChart from "../../charts/BarChart";

export default {
    name: 'CustomCode',
    components: {LineChart, BarChart},

    props: {
        widget: {required: true, type: Object}
    },

    data: () => ({
        loadedData: null,
        loading: true,
        showCode: false,
    }),

    mounted() {
        this.loadData()
    },

    methods: {
        loadData() {
            this.loading = true
            console.log('ok')
            axios.post(this.apiPath + "widgets/custom-code/data", {
                code: this.widget.code,
                chart_type: this.widget.chart_type,
                date_to: this.toDate,
                date_from: this.fromDate,
            }).then((response) => {
                this.loadedData = response.data
                this.loading = false
            }).catch((error) => {
                this.loading = false
                console.log(error.response.data)
                this.alertError(error.response.data.message ?? error.response.data.output )
            })
        },
        deleteChart() {
            this.$emit('delete')
        }
    },

    computed: {
        ...mapState({
            fromDate: state => state['dashboards'].fromDate,
            toDate: state => state['dashboards'].toDate
        }),
        chartLabels() {
            return Object.keys(this.loadedData).sort()
        },
        chartDataValues() {
            return this.chartLabels.map((label) => {
                return this.loadedData[label]
            })
        },
        chartData() {
            if (!this.loadedData) return
            return {
                labels: this.chartLabels,
                datasets: [
                    {
                        label: this.widget.title,
                        backgroundColor: this.widget.graph_color ? this.widget.graph_color : '#60A5FA',
                        data: this.chartDataValues
                    }
                ]
            }
        },
        chartOptions() {
            return {
                scales: {
                    yAxes: [
                        {
                            ticks: {
                                beginAtZero: false
                            }
                        }]
                },
                responsive: true,
                maintainAspectRatio: false,
            }

        }

    },

    watch: {
        fromDate() {
            this.loadData()
        },
        toDate() {
            this.loadData()
        }
    }
}
</script>
