<template>
    <div class="flex shadow-sm">
        <input
            v-model="fromDate"
            type="date"
            :max="maxDate"
            @input="changeDate"
            class="border-transparent flex-1 appearance-none border border-r-0 border-gray-300 w-full py-1 px-4 bg-white text-gray-700 dark:bg-notion-dark-light dark:text-gray-300 dark:placeholder-gray-600 placeholder-gray-400 text-base focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
        <div class="flex bg-white border-t border-b items-center px-2">
            To
        </div>
        <input
            v-model="toDate"
            type="date"
            :max="maxDate"
            @input="changeDate"
            class="border-transparent flex-1 appearance-none border border-l-0 border-gray-300 w-full py-1 px-4 bg-white text-gray-700 dark:bg-notion-dark-light dark:text-gray-300 dark:placeholder-gray-600 placeholder-gray-400 text-base focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
    </div>
</template>

<script>

import moment from "moment";

export default {
    name: 'DashboardDateRange',
    components: {},

    data: () => ({
        fromDate: null,
        toDate: null
    }),

    mounted () {
        this.init()
    },

    methods: {
        // Commit dates to store
        changeDate() {
            this.$store.commit('dashboards/setDateRange', {fromDate: this.fromDate, toDate: this.toDate})
        },
        init() {
            this.fromDate = moment().subtract(1,'month').format('YYYY-MM-DD')
            this.toDate = moment().format('YYYY-MM-DD')
            this.changeDate()
        }
    },

    computed: {
        maxDate () {
            return moment().format('YYYY-MM-DD')
        }
    }
}
</script>
