<template>
    <v-app>
        <v-navigation-drawer
            v-if="menu"
            v-model="drawer"
            :clipped="$vuetify.breakpoint.lgAndUp"
            fixed
            app
            >
            <v-list dense>
                <template v-for="item in items">
                <v-layout
                    v-if="item.heading"
                    :key="item.heading"
                    row
                    align-center
                >
                    <v-flex xs6>
                    <v-subheader v-if="item.heading">
                        {{ item.heading }}
                    </v-subheader>
                    </v-flex>
                    <v-flex xs6 class="text-xs-center">
                    <a href="#!" class="body-2 black--text">EDIT</a>
                    </v-flex>
                </v-layout>
                <v-list-group
                    v-else-if="item.children"
                    :key="item.text"
                    v-model="item.model"
                    :prepend-icon="item.model ? item.icon : item['icon-alt']"
                    append-icon=""
                >
                    <v-list-tile slot="activator">
                    <v-list-tile-content>
                        <v-list-tile-title>
                        {{ item.text }}
                        </v-list-tile-title>
                    </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile
                    v-for="child in item.children"
                    :key="child.name"
                    :to="{ name: child.name}"
                    >
                    <v-list-tile-action v-if="child.icon">
                        <v-icon>{{ child.icon }}</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>
                        {{ child.text }}
                        </v-list-tile-title>
                    </v-list-tile-content>
                    </v-list-tile>
                </v-list-group>
                <v-list-tile v-else :key="item.text" @click="abrir(item.name)">
                    <v-list-tile-action>
                    <v-icon>{{ item.icon }}</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                    <v-list-tile-title>
                        {{ item.text }}
                    </v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                </template>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar
            v-if="menu"
            :clipped-left="$vuetify.breakpoint.lgAndUp"
            color="blue darken-3"
            dark
            app
            fixed
            >
            <v-toolbar-title style="width: 300px" class="ml-0 pl-3">
                <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
                <span class="hidden-sm-and-down">{{ siteName }}</span>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon v-on:click="Home">
                <v-icon>home</v-icon>
            </v-btn>
            <v-btn icon>
                <v-icon>notifications</v-icon>
            </v-btn>
            <strong v-html="user.name"></strong>
            <v-btn icon large  v-on:click="Logout">
                <v-avatar size="32px" tile>
                    <v-icon>exit_to_app</v-icon>
                </v-avatar>
            </v-btn>
            </v-toolbar>
        <v-content>
            <v-container fluid>
                <router-view></router-view>
            </v-container>
        </v-content>
</v-app>
</template>
<script>
import {siteName} from '../../config';
import {mapGetters} from 'vuex';
import jwtToken from '../../helpers/jwt-token';
import {mapState} from 'vuex'

export default {
    data: () => ({
        siteName: siteName,
        menu: true,
        dialog: false,
        drawer: true,
        items: [
        {
          icon: 'keyboard_arrow_up',
          'icon-alt': 'keyboard_arrow_down',
          text: 'Administración',
          model: false,
          children: [
            { text: 'Usuarios', name: 'users' },
            { text: 'Roles', name: 'roles' },
          ]
        },
        { icon: 'settings', text: 'Settings' }
      ]
    }),
    computed: mapState({
			user: state => state.auth
		}),
    mounted(){

        // axios.get('/dash')
		// 		.then(res => {
        //            // console.log(res);
        //             this.menu = true;
        //             this.titulo = res.data.titulo;

        //             var user = res.data.user;
        //             if (user == null){
        //                 this.$router.push("/login");
        //             }else
        //                 this.setAuthUser(user);

		// 		})
		// 		.catch(err => {
        //             this.menu = false;
        //             this.$store.dispatch('unsetAuthUser');
        //             //console.log(err);
        //             if (err.request.status == 401){ // fallo de validated.
        //                 this.$router.push("/login");
        //             }
		// 		})

    },
    methods:{
        abrir(name){
            this.$router.push({path: name});
        },
        Home(){
            this.$router.push({name: 'dash'});
        },
        Logout() {
            //console.log('Logout');
            jwtToken.removeToken();
				this.$store.dispatch('unsetAuthUser')
					.then(() => {
                        //this.$noty.success('You are logged out');
                        this.$toast.success('Logout, hasta pronto...');
						this.$router.push({name: 'index'});
					});
        },
    }
  }
</script>
