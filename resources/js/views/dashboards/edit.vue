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
            <div class="w-full flex">
                <h2 class="text-xl font-medium mb-2 flex-grow">Update Dashboard</h2>
                <a @click.prevent="deleteDashboard" class="text-red-500 cursor-pointer hover:underline">Delete Dashboard</a>
            </div>
            <text-input :form="form" name="name" label="Name" :required="true"/>
            <text-input :form="form" name="description" label="Description" :required="true"/>
            <v-button class="mt-4" @click="update" :loading="loading">
                Update Dashboard
            </v-button>
        </div>
    </div>
</template>

<script>
import Form from 'vform'
import {mapGetters} from "vuex";

export default {
    name: 'EditDashboard',
    components: {},
    data() {
        return {
            loading: false,
            form: new Form({
                name: this.dashboard ? this.dashboard.name : '',
                description: this.dashboard ? this.dashboard.description : '',
            }),
        }
    },
    mounted() {
        if (!this.dashboard) {
            this.alertError('Please select/create a dashboard first.', true)
            this.$router.push({name: 'welcome'})
        }
        this.refreshForm()
    },
    computed: {
        ...mapGetters({
            dashboard: 'dashboards/current',
        }),
    },
    methods: {
        update() {
            this.loading = true

            this.form.put(this.apiPath + 'dashboards/' + this.dashboard.id, this.form.data()).then((response) => {
                this.$store.commit('dashboards/addOrUpdate', response.data)
                this.loading = false
                this.alertSuccess('Dashboard updated!')
                this.$router.push({name: 'welcome'})
            })
        },
        refreshForm() {
            if (this.dashboard) {
                this.form.name = this.dashboard.name
                this.form.description = this.dashboard.description
            }
        },
        deleteDashboard() {
            this.alertConfirm('Do you really want to delete this dashboard?',()=>{
                this.form.delete(this.apiPath + 'dashboards/' + this.dashboard.id).then((response) => {
                    this.$store.commit('dashboards/remove', this.dashboard)
                    this.loading = false
                    this.alertSuccess('Dashboard deleted!')
                    this.$router.push({name: 'welcome'})
                })
            })
        }
    },
    watch: {
        dashboard() {
            this.refreshForm()
        }
    }
}
</script>

