<template>
    <div class="period-total relative max-h-full max-w-full">
        <div class="bg-gradient-to-br from-white border shadow-sm max-h-full max-w-full h-full"
             :class="gradientColorClass">
            <div class="p-5 pb-8 h-full max-h-full max-w-full flex flex-col text-center group">
                <div class="graph-title">
                    <h3 class="font-medium text-lg truncate">{{ widget.title }}</h3>
                </div>
                <div class="relative max-h-full flex-grow" v-if="loadedData && !loading">
                    <div class="flex text-center justify-center items-center">
                        <div class="text-2xl font-semibold ml-16" :class="textColorClass">{{ loadedData.current_period }}
                        </div>
                        <div class="text-green-500 text-sm font-semibold ml-2" :class="monthlyChange>0?'text-green-500':'text-red-500'">
                             ({{ (monthlyChange>0?'+':'')}} {{ monthlyChange }}%)
                        </div>
                    </div>
                </div>
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
                    @click.prevent="deleteWidget"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                              clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script>

import axios from "axios";
import {mapState} from "vuex";

import colors from "../../../data/colors";

export default {
    name: 'PeriodTotal',
    components: {},

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
        deleteWidget() {
            this.$emit('delete')
        }
    },

    computed: {
        ...mapState({
            fromDate: state => state['dashboards'].fromDate,
            toDate: state => state['dashboards'].toDate
        }),
        colorName() {
            if (!this.widget.graph_color) return null;
            return colors.find((color) => color.value === this.widget.graph_color)
        },
        gradientColorClass() {
            return this.colorName ? 'to-' + this.colorName.name.toLocaleLowerCase() + '-100' : ''
        },
        textColorClass() {
            return this.colorName ? 'text-' + this.colorName.name.toLocaleLowerCase() + '-500' : 'text-black'
        },
        monthlyChange() {
            if (this.loadedData) {
                const num = (this.loadedData.current_period - this.loadedData.previous_period) * 100 / this.loadedData.previous_period
                return Math.round(num * 10) / 10
            }
            return null
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
