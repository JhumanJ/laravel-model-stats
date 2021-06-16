<template>
  <div id="model-stats-app">
    <loading ref="loading" />

    <transition name="page" mode="out-in">
        <router-view />
    </transition>
  </div>
</template>

<script>
import Loading from './Loading'

export default {
  el: '#model-stats',

  components: {
    Loading
  },

  data: () => ({
  }),

  mounted () {
    this.$loading = this.$refs.loading
  },

  methods: {}
}
</script>
