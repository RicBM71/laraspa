import Home from './components/home/Home.vue';
import Dash from './components/home/Dash.vue';
import Login from './components/login/Login.vue';

/*administrador*/

import UserIndex from './components/admin/users/UserIndex.vue';
import UserCreate from './components/admin/users/UserCreate.vue';
import UserEdit from './components/admin/users/UserEdit.vue';
//import RoleIndex from './components/admin/roles/RoleIndex.vue';
//import Permisos  from './components/admin/roles/Permisos.vue';

/*fin administrador */

//import ProfileWrapper from './components/profile/ProfileWrapper.vue';
import Profile from './components/profile/Profile.vue';
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
        name: 'dash',
		component: Dash,
		children: [
			{
				path: '',
				name: 'profile',
				component: Profile,
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
				path: '*',
				redirect: {
					name: '404'
				}
			}
		]
	},
];
