<template>
  <div class="relative">
    <v-select v-model="form[name]"
              :data="options"
              :label="label"
              :option-key="optionKey"
              :emit-key="optionKey"
              :required="required"
              :multiple="multiple"
    >
      <template #selected="{option}">
        <template v-if="multiple">
          <div class="flex items-center">
            <span v-for="(item,index) in option" :key="item">
              <span v-if="index!==0">, </span>
              {{ getOptionName(item) }}
            </span>
          </div>
        </template>
        <template v-else>
          <div class="flex items-center">
            {{ getOptionName(option) }}
          </div>
        </template>
      </template>
      <template #option="{option, selected}">
        <div class="flex items-center space-x-3 hover:text-white">
          <p class="flex-grow hover:text-white">
            {{ option.name }}
          </p>
          <span :class="{'hidden':!selected, 'font-semibold':selected}" class="absolute inset-y-0 right-0 flex items-center pr-4">
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                    clip-rule="evenodd"
              />
            </svg>
          </span>
        </div>
      </template>
    </v-select>
    <has-error :form="form" :field="name" />
  </div>
</template>

<script>
/**
 * Options: {name,value} objects
 */
export default {
  name: 'SelectInput',

  props: {
    options: { type: Array, required: true },
    optionKey: { type: String, default: 'value' },
    label: { type: String, default: null },
    required: { type: Boolean, default: false },
    multiple: { type: Boolean, default: false },
    form: { type: Object, required: true },
    name: { type: String, required: true }
  },

  data: () => ({}),

  watch: {},

  created () {},

  methods: {
    getOptionName (val) {
      return this.options.find((option) => {
        return option.value === val
      }).name
    }
  }
}
</script>
