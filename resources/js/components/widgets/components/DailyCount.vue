<template>
    <div class="p-1 w-full md:w-1/2">
        <div class="bg-white border shadow-sm p-5 h-full flex flex-col relative">
            <h3 class="font-medium text-xl truncate">{{ widget.title }} <span class="text-sm font-light text-gray-400">
                {{widget.aggregate_type}}, {{widget.model.name}}({{widget.date_column}})
            </span></h3>
            <line-chart v-if="loadedData && !loading" :options="{}" :chart-data="chartData"/>
            <div class="flex items-center justify-center flex-grow" v-else-if="loading">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 animate-spin" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                </svg>
            </div>
            <button class="absolute top-3 right-3 p-2 rounded-full bg-white hover:bg-gray-50 cursor-pointer"
                @click.prevent="deleteChart"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</template>

<script>

import axios from "axios";
import {mapState} from "vuex";
import LineChart from "../../charts/LineChart";

export default {
    name: 'DailyCount',
    components: {LineChart},

    props: {
        widget: {required: true, type: Object}
    },

    data: () => ({
        loadedData: null,
        loading: true,
    }),

    mounted() {
        this.loadData()
    },

    methods: {
        loadData() {
            this.loading = true
            axios.post(this.apiPath + "widgets/data", {
                model: this.widget.model.value,
                aggregate_type: this.widget.aggregate_type,
                date_column: this.widget.date_column,
                date_to: this.toDate,
                date_from: this.fromDate,
            }).then((response) => {
                this.loadedData = response.data
                this.loading = false
            })
        },
        deleteChart() {
            this.$store.commit('widgets/remove', this.widget)
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
            return this.chartLabels.map((label)=> {
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
