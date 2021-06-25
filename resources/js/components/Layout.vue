<template>
    <div id="model-stats-layout" class="max-w-4xl mx-auto pb-5">
        <div class="flex items-center py-4">
            <h1 class="mb-0 ml-3 font-medium text-2xl hover:font-black">
                <router-link :to="{name:'welcome'}" class="text-gray-900 hover:text-blue-500">
                ModelStats {{ appName }}
                </router-link>
            </h1>
        </div>
        <slot/>
    </div>
</template>

<script>

export default {
    components: {},

    data: () => ({}),

    mounted() {
    },

    methods: {},

    computed: {
        appName() {
            return window.ModelStats.config.appName ? ' - ' + window.ModelStats.config.appName : ''
        }
    }
}
</script>
