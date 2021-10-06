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
            <p>
                <a href="#" v-if="!customCode" @click.prevent="customCode=true">Create a graph with custom PHP code</a>
                <a href="#" v-else @click.prevent="customCode=false">Create a no-code graph</a>
            </p>
            <template v-if="!customCode">
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
            </template>
            <template v-else>
                <text-input :form="customCodeForm"
                            class="mt-2"
                            name="title"
                            label="title"
                            :required="true"/>
                <select-input :form="customCodeForm"
                              name="chart_type"
                              label="Chart Type"
                              :required="true"
                              :options="[{ value: 'bar_chart',name: 'Bar Chart'},{ value: 'line_chart',name: 'Line Chart'}]"/>
                <select-input :form="customCodeForm"
                              class="mt-2"
                              name="graph_color"
                              label="Graph Color"
                              :required="true"
                              :options="colorOptions"/>

                <code-input class="mt-2" label="Custom Code" @execute="executeCode" />
                <has-error :form="customCodeForm" field="code" />
                <code-output class="mt-2" :chart-type="customCodeForm.chart_type"  :value="executionResult" v-if="executionResult" />
                <has-error :form="customCodeForm" field="execution_result" />

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
import CodeInput from "../../components/custom-code/CodeInput";
import CodeOutput from "../../components/custom-code/CodeOutput";
import axios from "axios";

export default {
    name: 'WidgetCreate',
    components: {CodeOutput, CodeInput, TextInput, ModelInput},
    data() {
        return {
            customCode: false,
            form: new Form({
                id: null,
                model: null,
                aggregate_type: null,
                date_column: null,
                aggregate_column: null,
                graph_color: '#60A5FA'
            }),
            customCodeForm: new Form({
                id: null,
                title: null,
                custom_code: true,
                chart_type: 'line_chart',
                graph_color: '#60A5FA',
                code: null
            }),
            executionResult: null
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
    watch: {
      'customCodeForm.chart_type': function() {
          this.executionResult = null
      }
    },
    methods: {
        executeCode(code) {
            this.customCodeForm.code = code
            this.customCodeForm.post(this.apiPath+'widgets/custom-code/execute').then(({data}) => {
                this.executionResult = data
            });
        },
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
            if (this.customCode) {
                this.customCodeForm.clear()
                if (this.codeFormValid()) {
                    if (this.customCodeForm.id === null) this.customCodeForm.id = this.generateWidgetHash()
                    this.$store.commit('widgets/addOrUpdate', this.customCodeForm.data())
                    this.alertSuccess('Widget was created.', true)
                    this.$router.push({name: 'welcome'})
                }
            } else {
                this.form.clear()
                if (this.noCodeFormValid()) {
                    if (this.form.id === null) this.form.id = this.generateWidgetHash()
                    this.$store.commit('widgets/addOrUpdate', this.form.data())
                    this.alertSuccess('Widget was created.', true)
                    this.$router.push({name: 'welcome'})
                }
            }
        },
        noCodeFormValid() {
            const errors = {}
            let hasError = false
            if (!this.form.title) {
                errors['title'] = 'Please select a title.'
                hasError = true
            }
            if (!this.form.model) {
                errors['model'] = 'Please select a model.'
                hasError = true
            }
            if (!this.form.aggregate_type) {
                errors['aggregate_type'] = 'Please select an aggregate type.'
                hasError = true
            }
            if (!this.form.date_column) {
                errors['model'] = 'Please select the date column.'
                hasError = true
            }
            this.form.errors.set(errors)
            return !hasError
        },
        codeFormValid() {
            const errors = {}
            let hasError = false
            if (!this.customCodeForm.title) {
                errors['title'] = 'Please select a title.'
                hasError = true
            }
            if (!this.customCodeForm.code) {
                errors['code'] = 'Please type some code and execute it.'
                hasError = true
            }
            else if (!this.executionResult || !(this.executionResult.code_executed && this.executionResult.valid_output)) {
                errors['execution_result'] = 'Please make sure your code executes and returns data in the right format.'
                hasError = true
            }
            this.customCodeForm.errors.set(errors)
            return !hasError
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

