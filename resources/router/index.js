import {createWebHistory, createRouter} from 'vue-router'

import Home from '../pages/Home.vue'
import Registration from '../pages/Registration.vue'
import Login from '../pages/Login.vue'
import UserPage from '../pages/UserPage.vue'
import Dashboard from '../pages/Dashboard.vue'
import Chat from '../pages/Chat.vue'
import Possible_chats from '../pages/Possible_chats.vue'
import Users_chats from '../pages/Users_chats.vue'

export const routes = [
    {
        name: 'Home',
        path: '/',
        component: Home
    },
    {
        name: 'Login',
        path: '/login',
        component: Login
    },
    {
        name: 'Registration',
        path: '/registration',
        component: Registration
    },
    {
        name: 'UserPage',
        path: '/user/:id',
        component: UserPage
    },
    {
        name: 'Dashboard',
        path: '/dashboard',
        component: Dashboard
    },
    {
        name: 'Chat',
        path: '/chat/:id',
        component: Chat
    },
    {
        name: 'Possible_chats',
        path: '/youmaytalkto',
        component: Possible_chats
    },
    {
        name: 'Users_chats',
        path: '/userschats',
        component: Users_chats
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes
});

export default router;