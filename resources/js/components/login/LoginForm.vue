<template>
    <v-content>
      <v-container>
        <v-layout align-center justify-center>
          <v-flex xs12 sm8 md4>
            <v-card class="elevation-12">
                <v-toolbar dark color="primary">
                    <v-toolbar-title>Login</v-toolbar-title>
                    <v-spacer></v-spacer>

                </v-toolbar>
                <v-form @submit.prevent="login">
                    <v-card-text>
                        <v-text-field
                            prepend-icon="person"
                            name="username"
                            v-model="form.username"
                            v-validate="'required'"
                            :error-messages="errors.collect('username')"
                            label="Usuario"
                            data-vv-name="username"
                            required
                        ></v-text-field>

                        <v-text-field
                            prepend-icon="lock"
                            v-model="form.password"
                            v-validate="'required'"
                            :error-messages="errors.collect('password')"
                            data-vv-name="password"
                            name="password"
                            label="Password"
                            id="password"
                            type="password"
                        ></v-text-field>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" type="submit" :disabled="loading">
					        <span v-show="loading">Espere...</span>
					        <span v-show="!loading">Login</span>
                        </v-btn>

                    </v-card-actions>
                </v-form>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-content>
</template>

<script>
	import {api} from "../../config";

	export default {
        $_veeValidate: {
            validator: 'new'
        },
		data() {
			return {
				loading: false,
				form: {
					username: null,
					password: null
				},
				error: {
					username: null,
					password: null
				}
			}
		},
		methods: {
			login() {
                this.$validator.validateAll().then((result) => {
                if (result){
                    this.loading = true;
                    axios.post(api.login, this.form)
                        .then(res => {
                            this.loading = false;
                            //this.$toast.success('Bienvenido!');
                            this.$emit('loginSuccess', res.data);
                        })
                        .catch(err => {
                            (err.response.data.error) && this.$toast.error(err.response.data.error);

                            (err.response.data.errors)
                                ? this.setErrors(err.response.data.errors)
                                : this.clearErrors();

                            this.loading = false;
                        });
                    }
                  });
			},
			setErrors(errors) {
				this.error.username = errors.username ? errors.username[0] : null;
				this.error.password = errors.password ? errors.password[0] : null;
			},
			clearErrors() {
				this.error.username = null;
				this.error.password = null;
			}
		}
	}
</script>
