export default [
    { path: '/', name: 'welcome', component: require('@/views/dashboard.vue').default },
    {
        path: '/widgets/create',
        name: 'widgets.create',
        component: require('@/views/widgets/create.vue').default
    },

    {
        path: '/dashboards/create',
        name: 'dashboards.create',
        component: require('@/views/dashboards/create.vue').default
    },
    {
        path: '/dashboards/edit',
        name: 'dashboards.edit',
        component: require('@/views/dashboards/edit.vue').default
    },

    { path: '*', component: require('@/views/errors/404.vue').default }
]
