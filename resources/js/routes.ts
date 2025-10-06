import { createRouter, createWebHistory} from "vue-router";
import Login from "./pages/Login.vue";
import Register from "./pages/Register.vue";
import Dashboard from "./pages/Dashboard.vue";

const routes = [
    {
        path: '/',
        component: Dashboard,
    },
    {
        path: '/login',
        component: Login
    },
    {
        path: '/register',
        component: Register
    },
];

export const router = createRouter({
    history: createWebHistory(),
    routes,
})
