<template>
    <div v-if="registros">
        <v-layout row wrap>
			<v-dialog v-model="dialog" persistent max-width="290">
				<v-card>
					<v-card-title class="headline">Borrar Registro?</v-card-title>
					<v-card-text>¿Está seguro de borrar el registro seleccionado?.</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="blue darken-1" flat @click="dialog = false">Cancelar</v-btn>
					<v-btn color="blue darken-1" flat @click="deleteItem">Aceptar</v-btn>
					</v-card-actions>
				</v-card>
			</v-dialog>
			<v-flex xs12>
				<v-btn v-on:click="create" small >
					<v-icon small>add</v-icon> Crear Role
				</v-btn>
			</v-flex>
        </v-layout>
        <v-layout row wrap>
			<v-flex xs12>
				<v-data-table
				:headers="headers"
				:items="this.roles"

				>
					<template slot="items" slot-scope="props">
						<td>{{ props.item.id }}</td>
						<td class="text-xs-left">{{ props.item.name }}</td>
						<td class="text-xs-left">{{ props.item.guard_name }}</td>
						<td class="justify-center layout px-0">
							<v-icon
								small
								class="mr-2"
								@click="editItem(props.item.id)"
							>
								edit
							</v-icon>


							<v-icon
							small
							@click="openDialog(props.item.id)"
							>
							delete
							</v-icon>
						</td>
					</template>
					<template slot="pageText" slot-scope="props">
						Registros {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
					</template>
				</v-data-table>
			</v-flex>
		</v-layout>
    </div>
</template>
<script>
  export default {
    data () {
      return {
        headers: [
          {
            text: 'ID',
            align: 'center',
            value: 'id'
          },
          {
            text: 'Nombre',
            align: 'left',
            value: 'name'
          },
          { text: 'Guard', align: 'left', value: 'guard_name' },
          { text: 'Acciones', align: 'left', value: 'acciones', sortable: false, }
        ],
        roles:[],

		registros: false,
        dialog: false,
        role_id: 0
      }
    },
    mounted()
    {

        axios.get('admin/roles')
            .then(res => {

                this.roles = res.data.roles;
                //console.log(res.data);
                this.registros = true;
            })
    },
    methods:{
        create(){
            this.$router.push({ name: 'roles_create', params: { id: '0' } })
        },
        editItem (id) {
            this.$router.push({ name: 'roles_edit', params: { id: id } })
        },
        openDialog (id){
            this.dialog = true;
            this.role_id = id;
        },
        deleteItem () {
            this.dialog = false;

            axios.post('/admin/roles/'+this.role_id,{_method: 'delete'})
            .then(response => {
                this.$toast.success('Role borrado correctamente');
                this.roles = response.data;

                })
            .catch(err => {
                if (err.request.status == 403){
                    var data = JSON.parse(err.request.response);
                    this.status = true;
                    this.$toast.error(data.message);
                }else{
                    this.$toast.error("No se ha podido procesar la petición: ("+err+")");
                }

            });

        },

    }
  }
</script>
