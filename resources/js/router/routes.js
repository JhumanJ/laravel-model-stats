export default [
    { path: '/', name: 'welcome', component: require('@/views/dashboard.vue').default },
    {
        path: '/widgets/create',
        name: 'widgets.create',
        component: require('@/views/widgets/create.vue').default
    },

    { path: '*', component: require('@/views/errors/404.vue').default }
]
