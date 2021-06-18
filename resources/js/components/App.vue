<template>
  <div id="model-stats-app">
    <loading ref="loading" />

    <layout>
        <router-view />
    </layout>
  </div>
</template>

<script>
import Loading from './Loading'
import Layout from './Layout'

export default {
  el: '#model-stats',

  components: {
    Loading, Layout
  },

  data: () => ({
  }),

  mounted () {
    this.$loading = this.$refs.loading
  },

  methods: {}
}
</script>
