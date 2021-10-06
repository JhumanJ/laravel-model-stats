<template>

        <grid-layout
            id="widgets"
            class="w-full"
            v-if="gridLayout && gridLayout.length"
            :layout.sync="gridLayout"
            :responsive="true"
            :is-draggable="true"
            :is-resizable="true"
            :vertical-compact="true"
            :use-css-transforms="true"
            :rowHeight="100"
            @layout-updated="layoutUpdated"
        >
            <grid-item  v-for="widget in gridLayout"
                       :x="widget.x"
                       :y="widget.y"
                       :w="widget.w"
                       :h="widget.h"
                        :i="widget.id"
                       :key="widget.id">
                <component class="w-full h-full"
                           @delete="deleteWidget(widget)"
                           :is="getWidgetComponent(widget)"
                           :widget="widget"/>
            </grid-item>
        </grid-layout>
</template>

<script>
import DailyCount from "./components/DailyCount";
import GroupByCount from "./components/GroupByCount";
import PeriodTotal from "./components/PeriodTotal";
import CustomCode from "./components/CustomCode";
import VueGridLayout from 'vue-grid-layout';
import clone from 'lodash/clone'

export default {
    name: 'WidgetList',
    components: {
        PeriodTotal: PeriodTotal,
        DailyCount: DailyCount,
        GroupByCount: GroupByCount,
        CustomCode: CustomCode,
        GridLayout: VueGridLayout.GridLayout,
        GridItem: VueGridLayout.GridItem
    },

    props: {
        widgets: {type:Array, required:true}
    },

    data: () => ({
        typeToComponent: {
            'daily_count': 'daily-count',
            'cumulated_daily_count': 'daily-count',
            'period_total': 'period-total',
            'group_by_count': 'group-by-count',
        },
        gridLayout: []
    }),

    mounted () {
        this.initLayout()
    },

    methods: {
        getWidgetComponent(widget) {
            if (widget.custom_code) {
                return 'custom-code'
            }
            return this.typeToComponent[widget.aggregate_type]
        },
        initLayout() {
            this.gridLayout = clone(this.widgets).map((widget)=>{
                return {...widget.position, ...widget }
            })
        },
        layoutUpdated() {
            const widgets = this.gridLayout.map((widget)=>{
                const wid = clone(widget)
                wid.position = {
                    x: wid.x,
                    y: wid.y,
                    w: wid.w,
                    h: wid.h,
                    i: wid.i
                }
                delete wid.x
                delete wid.y
                delete wid.w
                delete wid.h
                delete wid.i
                return wid
            })
            this.$store.commit('widgets/set', widgets)
        },
        deleteWidget(widget){
            this.gridLayout = this.gridLayout.filter((wid)=>{
                return widget.id !== wid.id
            })
            this.$store.commit('widgets/remove', widget)
        }
    },

    computed: {},
    watch: {}
}
</script>
