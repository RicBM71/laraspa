<template>
	<div>
        <v-form>
            <v-container>
                <v-layout row wrap>
                    <v-flex sm1></v-flex>
                    <v-flex sm3>
                        <v-text-field
                            v-model="role.name"
                            v-validate="'required'"
                            :error-messages="errors.collect('name')"
                            label="Nombre"
                            data-vv-name="name"
                            data-vv-as="nombre"
                            required
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm1>
                        <v-text-field
                            v-model="role.guard_name"
                            v-validate="'required'"
                            :error-messages="errors.collect('guard_name')"
                            label="Guard"
                            data-vv-name="guard_name"
                            data-vv-as="Guard"
                            required
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm2>
                        <v-text-field
                            v-model="computedFModFormat"
                            label="Modificado"
                            readonly
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm2>
                        <v-text-field
                            v-model="computedFCreFormat"
                            label="Creado"
                            readonly
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm12>
                        <!-- <role-permisos v-if="this.role.id > 0" v-bind:role_id="this.role.id" v-bind:permiso_role="this.permiso_role"></role-permisos> -->
                        <v-container v-if="this.role.id > 0">
                            <h3>Permisos</h3>

                            <v-layout row wrap>
                                <v-flex sm2
                                    v-for="item in permisos"
                                    :key="item"
                                >
                                    <v-switch
                                        v-model="permiso_role"
                                        :label="item"
                                        :value="item"
                                        color="primary">
                                    ></v-switch>
                                </v-flex>
                            </v-layout>

                        </v-container>

                    </v-flex>
                    <v-flex sm5>
                    </v-flex>
                    <v-flex sm2>
                        <div class="text-xs-center">
                                    <v-btn @click="submit"  :loading="enviando" block  color="primary">
                            Guardar Role
                            </v-btn>
                        </div>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-form>

	</div>
</template>
<script>
import moment from 'moment'
import RolePermisos from './RolePermisos'

	export default {
		$_veeValidate: {
      		validator: 'new'
        },
        components: {
            'role-permisos': RolePermisos

		},
    	data () {
      		return {
                role: {
                    id:       0,
                    name:     "",
                    guard_name: "",
                    updated_at:"",
                    created_at:"",
                },
                titulo:   "Roles",
                role_id: "",

                permiso_role:[],
                permisos:[],

        		status: false,
                enviando: false,

      		}
        },
        mounted(){
            var id = this.$route.params.id;
            //console.log(this.$route.params);
            if (id > 0)
                axios.get('/admin/roles/'+id+'/edit')
                    .then(res => {
                        this.role = res.data.role;
                        this.permiso_role = res.data.permiso_role;
                        this.permisos = res.data.permisos;

                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'roles'})
                    })
        },

        computed: {
            computedFModFormat() {
                moment.locale('es');
                return this.role.updated_at ? moment(this.role.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.role.updated_at ? moment(this.role.created_at).format('D/MM/YYYY H:mm:ss') : '';
            }

        },
    	methods:{
            submit() {

                //console.log("Edit user (submit):"+this.role.id);
                this.enviando = true;

                var url = "/admin/roles";
                var metodo = "post";

                if (this.role.id > 0){
                    url += '/'+this.role.id;
                    metodo = "put";
                }

                this.$validator.validateAll().then((result) => {
                    if (result){
                        axios({
                            method: metodo,
                            url: url,
                            data:
                                {
                                    name: this.role.name,
                                    guard_name: this.role.guard_name,
                                    permissions: this.permiso_role
                                }
                            })
                            .then(response => {
                                this.$toast.success(response.data);
                                this.enviando = false;
                            })
                            .catch(err => {
                                console.log(err);
                                if (err.request.status == 422){ // fallo de validated.
                                    const msg_valid = err.response.data.errors;
                                    for (const prop in msg_valid) {
                                        this.$toast.error(`${msg_valid[prop]}`);
                                    }
                                }
                                this.enviando = false;
                            });
                        }
                    else{
                        this.enviando = false;
                    }
                });

            },

    }
  }
</script>
