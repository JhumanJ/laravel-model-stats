<template>
    <div id="create-widget">
        <div class="flex flex-col bg-white shadow-md p-6">
            <h2 class="text-xl font-medium mb-2">Create a Dashboard</h2>
            <text-input :form="form" name="name" label="Name" :required="true"/>
            <text-input :form="form" name="description" label="Description" :required="true"/>
            <v-button class="mt-4" @click="create" :loading="loading">
                Create Dashboard
            </v-button>
        </div>
    </div>
</template>

<script>
import Form from 'vform'

export default {
    name: 'CreateDashboard',
    components: {},
    data () {
        return {
            loading: false,
            form: new Form({
                name: null,
                description: null,
            }),
        }
    },
    computed: {

    },
    methods: {
        create() {
            this.loading = true
            this.form.post(this.apiPath + 'dashboards').then((response) => {
                this.$store.commit('dashboards/addOrUpdate', response.data)
                this.loading = false
                this.alertSuccess('Dashboard created!')
                this.$router.push({name: 'welcome'})
            })
        }
    }
}
</script>

