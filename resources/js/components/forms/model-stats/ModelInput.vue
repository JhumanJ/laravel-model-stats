<template>
    <div>
        <v-select :value="form[name]"
                  :data="models"
                  :label="label"
                  :option-key="optionKey"
                  :required="required"
                  v-model="form[name]"
                  @input="select"
        >
            <template #selected="{option}">
                <div class="flex items-center space-x-3">
          <span class="block truncate">
            {{ option.name }} <small class="truncate text-gray-300"> - {{ option.namespace }}</small>
          </span>
                </div>
            </template>
            <template #option="{option, selected}">
                <div class="flex items-center space-x-3 hover:text-white">
          <span class="block truncate"
                :class="{ 'font-normal' : !selected , 'font-semibold' : selected}"
          >
            {{ option.name }} <small class="truncate text-gray-300"> - {{ option.namespace }}</small>
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
        <has-error :form="form" :field="name"/>
    </div>
</template>

<script>

export default {
    name: 'ModelInput',
    components: {},
    props: {
        name: { required: true },
        optionKey: { type: String, default: 'value' },
        label: { type: String, default: null },
        required: { type: Boolean, default: false },
        form: { type: Object, required: true },
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
        models () {
            return window.ModelStats.models.map(function (model) {
                const name = model.class.substr(model.class.lastIndexOf('\\') + 1)
                return {
                    name: name,
                    namespace: model.class.replace('\\' + name, '').substr(1).replaceAll('\\', '/'),
                    value: model.class
                }
            })
        }
    }
}
</script>
