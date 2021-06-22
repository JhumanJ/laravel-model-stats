<template>
    <div id="widgets" class="w-full mt-5 flex flex-wrap">
        <component v-for="widget in widgets" :key="widget.id" :is="typeToComponent[widget.aggregate_type]" :widget="widget"/>
    </div>
</template>

<script>
import DailyCount from "./components/DailyCount";

export default {
    name: 'WidgetList',
    components: {DailyCount},

    props: {
        widgets: {type:Array, required:true}
    },

    data: () => ({
        typeToComponent: {
            'daily_count': 'daily-count'
        },
    }),

    mounted () {
    },

    methods: {},

    computed: {
        appName () {
        }
    }
}
</script>
