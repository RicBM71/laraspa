<template>
	<div>
        <login-form @loginSuccess="loginSuccess"></login-form>
	</div>
</template>

<script>
	import LoginForm from './../login/LoginForm.vue'
	import jwtToken from "../../helpers/jwt-token";
	import {mapActions} from "vuex";

	export default {
        mounted(){
          //  console.log('Login');
        },
		components: {
			'login-form': LoginForm
		},
		methods: {
			...mapActions([
				'setAuthUser'
			]),
			loginSuccess(data) {
				jwtToken.setToken(data.token);
				this.setAuthUser(data.user);
				this.$router.push({name: 'dash'});
			}
		}
	}
</script>
