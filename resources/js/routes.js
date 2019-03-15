import Home from './components/home/Home.vue';
import Dash from './components/home/Dash.vue';
import Main from './components/home/Main.vue';
import Login from './components/login/Login.vue';

/*administrador*/

import UserIndex from './components/admin/users/UserIndex.vue';
import UserCreate from './components/admin/users/UserCreate.vue';
import UserEdit from './components/admin/users/UserEdit.vue';
import RolesIndex from './components/admin/roles/RolesIndex.vue';
import RolesEdit from './components/admin/roles/RolesEdit.vue';
//import Permisos  from './components/admin/roles/Permisos.vue';

/*fin administrador */

//import ProfileWrapper from './components/profile/ProfileWrapper.vue';
//import Profile from './components/profile/Profile.vue';
//import EditProfile from './components/profile/edit-profile/EditProfile.vue';
//import EditPassword from './components/profile/edit-password/EditPassword.vue';

export default [
	{
		path: '/',
		name: 'index',
		component: Home,
		meta: {}
	},
	{
		path: '/login',
		name: 'login',
		component: Login,
		meta: {requiresGuest: true}
	},
	{
        path: '/dash',
		component: Dash,
		children: [
			{
				path: '',
				name: 'dash',
				component: Main,
				meta: {requiresAuth: true}
			},
            {
                path: '/users',
                name: 'users',
                component: UserIndex,
                meta: {requiresAuth: true}
            },
            {
                path: '/users/create',
                name: 'users_create',
                component: UserCreate,
                meta: {requiresAuth: true}
            },
            {
                path: '/users/:id/edit',
                name: 'users_edit',
                component: UserEdit,
                meta: {requiresAuth: true}
            },
            {
                path: '/roles',
                name: 'roles',
                component: RolesIndex,
                meta: {requiresAuth: true}
            },
            {
                path: '/roles/create',
                name: 'roles_create',
                component: RolesEdit,
                meta: {requiresAuth: true}
            },
            {
                path: '/roles/:id/edit',
                name: 'roles_edit',
                component: RolesEdit,
                meta: {requiresAuth: true}
            },

		]
    },
    {
        path: '*',
        redirect: {
            name: 'index'
        }
    }
];
