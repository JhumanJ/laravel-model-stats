<template>
    <div id="model-stats-layout" class="max-w-2xl mx-auto mb-5">
        <div class="flex items-center py-4">
            <h1 class="mb-0 ml-3 font-medium text-2xl">ModelStats {{appName}}</h1>
        </div>
        <transition name="page" mode="out-in">
            <slot/>
        </transition>
    </div>
</template>

<script>

export default {
    components: {},

    data: () => ({}),

    mounted () {
    },

    methods: {},

    computed: {
        appName () {
            return window.ModelStats.config.appName ? ' - ' + window.ModelStats.config.appName : ''
        }
    }
}
</script>
