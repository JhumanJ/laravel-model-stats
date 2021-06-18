<template>
  <div class="relative mb-3">
    <v-checkbox :id="id?id:name" v-model="form[name]" :name="name" @input="$emit('input',$event)">
      {{ label }}
    </v-checkbox>
    <has-error :form="form" :field="name" />
  </div>
</template>

<script>
import VCheckbox from './components/VCheckbox'
export default {
  name: 'CheckboxInput',
  components: { VCheckbox },
  props: {
    id: { type: String, default: null },
    name: { type: String, required: true },
    label: { type: String, required: true },
    form: { type: Object, required: true },
    placeholder: { type: String, default: null }
  },

  data: () => ({}),

  watch: {},

  mounted () {
    this.$emit('input', !!this.form[name])
  },

  created () {},

  methods: {}
}
</script>
