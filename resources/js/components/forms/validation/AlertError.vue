<template>
  <div v-if="form.errors.any()" class="bg-red-200 border-red-600 text-red-600 border-l-4 p-4 relative" role="alert">
    <button v-if="dismissible" type="button" class="close" aria-label="Close" @click="dismiss">
      <span aria-hidden="true">&times;</span>
    </button>

    <slot>
      <div v-if="form.errors.has('error')" v-html="form.errors.get('error')"/>
      <div v-else v-html="message"/>
    </slot>
  </div>
</template>

<script>
import Alert from './Alert'

export default {
  name: 'AlertError',

  extends: Alert,

  props: {
    message: {
      type: String,
      default: 'There were some problems with your input.'
    }
  }
}
</script>
