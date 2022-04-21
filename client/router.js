import Vue from 'vue'
import Router from 'vue-router'
import { scrollBehavior } from '~/utils'

Vue.use(Router)

const page = path => () => import(`~/pages/${path}`).then(m => m.default || m)

const routes = [
  { path: '/', name: 'welcome', component: page('welcome.vue') },

  { path: '/login', name: 'login', component: page('auth/login.vue') },
  { path: '/register', name: 'register', component: page('auth/register.vue') },
  { path: '/password/reset', name: 'password.request', component: page('auth/password/email.vue') },
  { path: '/password/reset/:token', name: 'password.reset', component: page('auth/password/reset.vue') },
  { path: '/email/verify/:id', name: 'verification.verify', component: page('auth/verification/verify.vue') },
  { path: '/email/resend', name: 'verification.resend', component: page('auth/verification/resend.vue') },

  { path: '/home', name: 'home', component: page('home.vue') },
  {
    path: '/settings',
    component: page('settings/index.vue'),
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      { path: 'profile', name: 'settings.profile', component: page('settings/profile.vue') },
      { path: 'password', name: 'settings.password', component: page('settings/password.vue') }
    ]
  },
  {
    path: '/credit',
    component: page('credit/index.vue'),
    children: [
      { path: '', redirect: { name: 'credit.create' } },
      { path: 'create', name: 'credit.create', component: page('credit/create.vue') },
      { path: 'list', name: 'credit.list', component: page('credit/list.vue') }
    ]
  },
  {
    path: '/debit',
    component: page('debit/index.vue'),
    children: [
      { path: '', redirect: { name: 'debit.create' } },
      { path: 'create', name: 'debit.create', component: page('debit/create.vue') },
      { path: 'list', name: 'debit.list', component: page('debit/list.vue') }
    ]
  },
  {
    path: '/board',
    component: page('board/index.vue'),
    children: [
      { path: '', redirect: { name: 'board.create' } },
      { path: 'create', name: 'board.create', component: page('board/create.vue') },
      { path: 'list', name: 'board.list', component: page('board/list.vue') },
      { path: 'show/:id', name: 'board.show', component: page('board/show.vue') },
      { path: 'edit/:id', name: 'board.edit', component: page('board/edit.vue') }
    ]
  }
]

export function createRouter () {
  return new Router({
    routes,
    scrollBehavior,
    mode: 'history'
  })
}
