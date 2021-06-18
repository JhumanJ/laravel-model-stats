<template>
  <div class="flex items-center">
    <input
      :id="id || name"
      :name="name"
      :checked="internalValue"
      type="checkbox"
      :class="sizeClasses"
      class="border-gray-500"
      @click="handleClick"
    >
    <label :for="id || name" class="text-gray-700 dark:text-gray-300 ml-2">
      <slot />
    </label>
  </div>
</template>

<script>
export default {
  name: 'VCheckbox',

  props: {
    id: { type: String, default: null },
    name: { type: String, default: 'checkbox' },
    value: { type: Boolean, default: false },
    checked: { type: Boolean, default: false },
    size: { type: String, default: 'normal' }
  },

  data: () => ({
    internalValue: false
  }),

  computed: {
    sizeClasses () {
      if (this.size === 'small') {
        return 'w-3 h-3'
      }
      return 'w-5 h-5'
    }
  },

  watch: {
    value (val) {
      this.internalValue = val
    },

    checked (val) {
      this.internalValue = val
    },

    internalValue (val, oldVal) {
      if (val !== oldVal) {
        this.$emit('input', val)
      }
    }
  },

  created () {
    this.internalValue = this.value

    if ('checked' in this.$options.propsData) {
      this.internalValue = this.checked
    }
  },

  methods: {
    handleClick (e) {
      this.$emit('click', e)

      if (!e.isPropagationStopped) {
        this.internalValue = e.target.checked
      }
    }
  }
}
</script>
