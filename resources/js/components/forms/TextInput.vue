<template>
  <div class="relative mb-3">
    <label v-if="label" :for="id?id:name" class="text-gray-700 dark:text-gray-300 uppercase font-bold text-xs">
      {{ label }}
      <span v-if="required" class="text-red-500 required-dot">*</span>
    </label>
    <input :id="id?id:name" v-model="form[name]"
           :type="nativeType" :class="{ 'ring-red-500 ring-2': form.errors.has(name) }"
           class="border-transparent flex-1 appearance-none border border-gray-300 w-full py-2 px-4 bg-white text-gray-700 dark:bg-notion-dark-light dark:text-gray-300 dark:placeholder-gray-600 placeholder-gray-400 shadow-sm text-base focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
           :name="name"
           :placeholder="placeholder"
    >
    <has-error :form="form" :field="name" />
  </div>
</template>

<script>
export default {
  name: 'TextInput',

  props: {
    id: { type: String, default: null },
    nativeType: { type: String, default: 'text' },
    name: { type: String, required: true },
    label: { type: String, required: false },
    form: { type: Object, required: true },
    required: { type: Boolean, default: false },
    placeholder: { type: String, default: null }
  },

  data: () => ({}),

  watch: {},

  created () {},

  methods: {

  }
}
</script>
