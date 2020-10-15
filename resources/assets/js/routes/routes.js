import DashboardLayout from '../components/Dashboard/Layout/DashboardLayout.vue'
// GeneralViews
import NotFound from '../components/GeneralViews/NotFoundPage.vue'
import Vue from "vue";
import Router from "vue-router";
// Admin pages
import Overview from '../components/Dashboard/Views/Overview.vue'
import UserProfile from '../components/Dashboard/Views/UserProfile.vue'
import ProjectList from '../components/Dashboard/Views/ProjectList.vue'
import Typography from '../components/Dashboard/Views/Typography.vue'
import Icons from '../components/Dashboard/Views/Icons.vue'
import Notifications from '../components/Dashboard/Views/Notifications.vue'
import Events from '../components/Dashboard/Views/Events.vue'
import Login from '../components/GeneralViews/Login.vue'
import Register from '../components/GeneralViews/Register.vue'

Vue.use(Router);


const router = new Router({
  mode: "history", // https://router.vuejs.org/api/#mode
  linkActiveClass: "open active",
  scrollBehavior: () => ({ y: 0 }),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: Login,
    },
    {
      path: '/register',
      name: 'Register',
      component: Register,
    },
    {
      path: '/',
      component: DashboardLayout,
      redirect: '/events',
      children: [
        {
          path: 'overview',
          name: 'Overview',
          component: Overview
        },
        {
          path: 'user',
          name: 'User',
          component: UserProfile
        },
        {
          path: 'events',
          name: 'Events',
          component: Events
        },
        {
          path: 'project-list',
          name: 'Project List',
          component: ProjectList
        },
        {
          path: 'typography',
          name: 'Typography',
          component: Typography
        },
        {
          path: 'icons',
          name: 'Icons',
          component: Icons
        },
        {
          path: 'notifications',
          name: 'Notifications',
          component: Notifications
        }
      ]
    },
    { path: '*', component: NotFound }

  ]
});

router.beforeEach((to, from, next) => {
  const authToken = localStorage.getItem("token");
  if (to.path !== '/login') {
      if (to.path === '/register') {
        next();
      } else {
        if(authToken === null || authToken === 'undefined') {
          next('login');
        } else {
          next();
        }
      }
  } else {
    next();
  }
});
export default router
