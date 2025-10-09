import { createRouter, createWebHistory} from "vue-router";
import Login from "./pages/Login.vue";
import Register from "./pages/Register.vue";
import Dashboard from "./pages/Dashboard.vue";
import Transaction from "./pages/Transaction.vue";
import Transfer from "./pages/Transfer.vue";
import useUser from "@/composables/useUser";


const routes = [
    {
        path: '/',
        name: 'Dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    },
    {
        path: '/transactions',
        name: 'Transactions',
        component: Transaction,
        meta: { requiresAuth: true }
    },
    {
        path: '/transactions/transfer',
        name: 'Transfer',
        component: Transfer,
        meta: { requiresAuth: true }
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    const { user, fetchUser } = useUser();
    if (to.meta.requiresAuth && !user.value) {
        if (!user.value) {
            next('/login');
        } else {
            next();
        }
    } else {
        next();
    }
});

export { router };
