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
            <model-input :form="form" name="model" label="Model" @input="modelSelected" :required="true"/>
            <!--     TODO: widget type selection       -->
            <!--     Select date field       -->
            <!--     Select aggregate type: daily count, daily average       -->
            <!--     Select aggregate column        -->
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
                              :required="true"
                              :options="modelColumnOptions"/>
                <select-input
                    v-if="aggregate_type && aggregate_type.requires_agg_column"
                    :form="form"
                    class="mt-2"
                    name="aggregate_column"
                    label="Aggregate Field"
                    :required="true"
                    :options="modelColumnOptions"/>
            </template>
        </div>
    </div>
</template>

<script>
import Form from 'vform'
import ModelInput from '@/components/forms/model-stats/ModelInput'

export default {
    name: 'WidgetCreate',
    components: { ModelInput },
    data () {
        return {
            form: new Form({
                model: null,
                aggregate_type: null,
                date_column: null,
                aggregate_column: null
            }),
        }
    },
    computed: {
        modelAndTypeSelected () {
            return this.form.model !== null && this.form.aggregate_type !== null
        },
        modelColumns () {
            if (!this.form.model) return []
            return window.ModelStats.models.find((model) => {
                return model.class === this.form.model.value
            }).fields
        },
        modelColumnOptions () {
            return this.modelColumns.map((field) => {
                return {
                    name: field,
                    value: field
                }
            })
        },
        aggregateType () {
            if (this.form.aggregate_type == null) return null
            return this.aggregateTypes.find((type) => {
                return type.value === this.form.aggregate_type
            })
        },
        aggregateTypes () {
            return [
                {
                    value: 'daily_count',
                    name: 'Daily Count',
                    requires_agg_column: false
                },
                {
                    value: 'daily_avg',
                    name: 'Daily Average',
                    requires_agg_column: true
                }
            ]
        }
    },
    methods: {
        modelSelected () {
            console.log(this.form.model)
            if (this.form.model) {
                console.log(this.modelColumns, this.modelColumns.includes('created_at'))
                if (this.modelColumns.includes('created_at')) {
                    this.form.date_column = 'created_at'
                } else if (this.modelColumns.includes('updated_at')) {
                    this.form.date_column = 'updated_at'
                }
            }
        }
    }
}
</script>

