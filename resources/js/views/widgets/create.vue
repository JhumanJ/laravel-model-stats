<template>
    <div id="create-widget">
        <router-link :to="{name:'welcome'}" class="flex items-center mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to dashboard
        </router-link>
        <div class="flex flex-col bg-white shadow-md p-6">
            <h2 class="text-xl font-medium mb-2">Create a Widget</h2>
            <text-input :form="form"
                        class="mt-2"
                        name="title"
                        label="title"
                        :required="true"/>
            <model-input :form="form" name="model" label="Model" @input="modelSelected" :required="true"/>
            <select-input :form="form"
                          class="mt-2"
                          name="aggregate_type"
                          label="Aggregate Type"
                          :required="true"
                          :options="aggregateTypes"/>

            <template v-if="modelAndTypeSelected">
                <select-input :form="form"
                              class="mt-2"
                              name="date_column"
                              label="Date Column"
                              :required="true" v-if="hasDateColumn"
                              :options="modelColumnOptions"/>
                <select-input :form="form"
                              class="mt-2"
                              name="graph_color"
                              label="Graph Color"
                              :required="true"
                              :options="colorOptions"/>
                <select-input
                    v-if="aggregateType && aggregateType.requires_agg_column"
                    :form="form"
                    class="mt-2"
                    name="aggregate_column"
                    label="Aggregate Field"
                    :required="true"
                    :options="modelColumnOptions"/>
                <v-button class="mt-4" @click="createWidget">
                    Create Widget
                </v-button>
            </template>
        </div>
    </div>
</template>

<script>
import Form from 'vform'
import ModelInput from '@/components/forms/model-stats/ModelInput'
import TextInput from "../../components/forms/TextInput";
import colors from "../../data/colors";

export default {
    name: 'WidgetCreate',
    components: {TextInput, ModelInput},
    data() {
        return {
            form: new Form({
                id: null,
                model: null,
                aggregate_type: null,
                date_column: null,
                aggregate_column: null,
                graph_color: '#60A5FA'
            }),
        }
    },
    computed: {
        modelAndTypeSelected() {
            return this.form.model !== null && this.form.aggregate_type !== null
        },
        modelColumns() {
            if (!this.form.model) return []
            return window.ModelStats.models.find((model) => {
                return model.class === this.form.model.value
            }).fields
        },
        modelColumnOptions() {
            return this.modelColumns.map((field) => {
                return {
                    name: field,
                    value: field
                }
            })
        },
        aggregateType() {
            if (this.form.aggregate_type == null) return null
            return this.aggregateTypes.find((type) => {
                return type.value === this.form.aggregate_type
            })
        },
        hasDateColumn() {
            if (this.form.aggregate_type == null) return null
            return ![/* TODO add columns without date */].includes(this.form.aggregate_type)
        },
        aggregateTypes() {
            return [
                {
                    value: 'daily_count',
                    name: 'Daily Count',
                    requires_agg_column: false
                },
                {
                    value: 'cumulated_daily_count',
                    name: 'Cumulated Daily Count',
                    requires_agg_column: false
                },
                {
                    value: 'period_total',
                    name: 'Period Total Count',
                    requires_agg_column: false
                },
                {
                    value: 'group_by_count',
                    name: 'Group By Count',
                    requires_agg_column: true
                },
            ]
        },
        colorOptions() {
            return colors
        }
     },
    methods: {
        modelSelected() {
            if (this.form.model) {
                if (this.modelColumns.includes('created_at')) {
                    this.form.date_column = 'created_at'
                } else if (this.modelColumns.includes('updated_at')) {
                    this.form.date_column = 'updated_at'
                }
            }
        },
        createWidget() {
            if (this.formIsValid()) {
                // this.form.post()
                if (this.form.id === null) this.form.id = this.generateWidgetHash()
                this.$store.commit('widgets/addOrUpdate', this.form.data())
                this.alertSuccess('Widget was created.', true)
                this.$router.push({name: 'welcome'})
            }
        },
        formIsValid() {
            if (!this.form.title) {
                this.form.errors.set('title', 'Please select a title.')
                return false
            }
            if (!this.form.model) {
                this.form.errors.set('model', 'Please select a model.')
                return false
            }
            if (!this.form.aggregate_type) {
                this.form.errors.set('aggregate_type', 'Please select an aggregate type.')
                return false
            }
            if (!this.form.date_column) {
                this.form.errors.set('model', 'Please select the date column.')
                return false
            }
            return true
        },
        /**
         * Generate a unique hash id for the widget
         */
        generateWidgetHash() {
            let s = JSON.stringify(this.form) + Date.now().toString()
            return s.split('').reduce((prevHash, currVal) =>
                (((prevHash << 5) - prevHash) + currVal.charCodeAt(0)) | 0, 0);
        }
    }
}
</script>

