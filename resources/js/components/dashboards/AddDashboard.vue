<template>
    <router-link :to="{name:'dashboards.create'}" id="add-dashboard" class="bg-white text-gray-500 hover:text-blue-500 p-2 relative shadow-sm border border-gray-300 hover:bg-gray-100 cursor-pointer flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd" />
        </svg>
    </router-link>
</template>

<script>

export default {
    name: 'AddDashboard',
    components: {},

    data: () => ({}),

    mounted () {
    },

    methods: {},

    computed: {
        appName () {
        }
    }
}
</script>
