<template>
    <div class="flex flex-col" v-if="dashboardsLoading">

    </div>
    <div class="flex flex-col" v-else>
        <div class="flex px-2">
            <template v-if="dashboard">
                <div class="w-56">
                    <dashboard-input name="dashboard" :value="dashboard" @input="changeDashboard"/>
                </div>
                <edit-dashboard class="ml-1"/>
                <add-dashboard class="ml-1"/>
                <div class="flex-grow"/>
                <add-widget/>
                <button id="save-dashboard" @click.prevent="saveDashboard"
                        class="ml-1 bg-white text-gray-500 hover:text-blue-500 p-2 relative shadow-sm border hover:bg-gray-100 cursor-pointer flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z"/>
                    </svg>
                </button>
            </template>
            <template v-else>
                <router-link id="create-dashboard" :to="{'name':'dashboards.create'}"
                             class="bg-white text-gray-500 hover:text-blue-500 p-2 relative shadow-sm border hover:bg-gray-100 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z"
                              clip-rule="evenodd"/>
                    </svg>
                </router-link>
            </template>
        </div>
        <div class="flex flex-wrap mt-2" v-if="dashboard">
            <p class="ml-4">{{ dashboard.description }}</p>

            <div v-if="!hasWidgets" class="bg-white w-full shadow-sm p-6 mt-4">
                You don't have any widgets yet.
                <router-link :to="{'name':'widgets.create'}">Click here to create one</router-link>
                .
            </div>

            <template v-else>
                <div class="w-full mt-5 px-2">
                    <div class="max-w-sm">
                        <dashboard-date-range/>
                    </div>
                </div>
                <widget-list class="mt-5" :widgets="widgets"/>
            </template>
        </div>

        <div v-else class="bg-white shadow-sm p-6 mt-6">
            You don't have any dashboard yet.
            <router-link :to="{'name':'dashboards.create'}">Click here to create one</router-link>
            .
        </div>
    </div>
</template>

<script>
import AddWidget from '../components/widgets/AddWidget'
import AddDashboard from "../components/dashboards/AddDashboard"
import DashboardInput from "../components/forms/model-stats/DashboardInput"
import {mapGetters, mapState} from 'vuex'
import WidgetList from "../components/widgets/WidgetList";
import axios from "axios";
import DashboardDateRange from "../components/dashboards/DashboardDateRange";
import EditDashboard from "../components/dashboards/EditDashboard";

export default {
    name: 'Dashboard',
    components: {EditDashboard, DashboardDateRange, WidgetList, AddWidget, AddDashboard, DashboardInput},

    computed: {
        ...mapGetters({
            dashboard: 'dashboards/current',
        }),
        ...mapState({
            dashboardsLoading: state => state['dashboards'].loading,
            widgets: state => state['widgets'].content
        }),
        hasWidgets() {
            if (!this.dashboard) return false;
            return this.widgets.length > 0;
        }
    },

    methods: {
        changeDashboard(selection) {
            this.$store.commit('dashboards/setCurrentId', selection.value)
            this.$store.commit('widgets/set', this.dashboard.body.widgets)
        },
        saveDashboard() {
            const dashboard = this.dashboard;
            if (!dashboard.body) dashboard.body = {}
            dashboard.body.widgets = this.widgets;

            this.$store.commit('dashboards/loads')
            axios.put(this.apiPath + 'dashboards/' + dashboard.id, dashboard).then((response) => {
                this.$store.commit('dashboards/stoppedLoading')
                // No need to refresh store data as we don't have any back-end transformations yet
                this.$store.commit('dashboards/addOrUpdate', response.data)
                this.$store.commit('widgets/set', this.dashboard.body.widgets)
                this.alertSuccess('Dashboard was successfully saved.')
            })
        }
    }
}
</script>

