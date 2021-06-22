<template>
    <div>
        <v-select :value="value"
                  :data="dashboardOptions"
                  :label="label"
                  :option-key="optionKey"
                  :required="required"
                  @input="select"
        >
            <template #selected="{option}">
                <div class="flex items-center space-x-3">
          <span class="block truncate">
            {{ option.name }}
          </span>
                </div>
            </template>
            <template #option="{option, selected}">
                <div class="flex items-center space-x-3 hover:text-white">
          <span class="block truncate"
                :class="{ 'font-normal' : !selected , 'font-semibold' : selected}"
          >
            {{ option.name }}
          </span>
                </div>

                <span :class="{'hidden':!selected}" class="absolute inset-y-0 right-0 flex items-center pr-4">
          <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                  clip-rule="evenodd"
            />
          </svg>
        </span>
            </template>
        </v-select>
    </div>
</template>

<script>

import {mapState} from "vuex";

export default {
    name: 'DashboardInput',
    components: {},
    props: {
        name: { required: true },
        value: {},
        optionKey: { type: String, default: 'value' },
        label: { type: String, default: null },
        required: { type: Boolean, default: false },
    },
    data () {
        return {}
    },
    methods: {
        select (value) {
            this.$emit('input', value)
        },
    },
    computed: {
        dashboardOptions () {
            return this.dashboards.map(function (dashboard) {
                return {
                    name: dashboard.name,
                    value: dashboard.id
                }
            })
        },
        ...mapState({
            dashboards: state => state['dashboards'].content,
        })
    }
}
</script>
