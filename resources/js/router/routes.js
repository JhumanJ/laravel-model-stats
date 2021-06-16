function view (path) {
  return () => import(/* webpackChunkName: '' */ `../views/${path}`).then(m => m.default || m)
}

export default [
  { path: '/', name: 'welcome', component: view('welcome.vue') },

  { path: '*', component: view('errors/404.vue') }
]
