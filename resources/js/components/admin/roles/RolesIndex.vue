<template>
    <div v-if="registros">
        <v-card>
            <v-card-title>
                <h2>{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-container>
            <v-row>
                <v-col cols="12">
                    <v-data-table
                    :headers="headers"
                    :items="roles"
                    >

                        <template v-slot:item.permissions="{ item }">
                            {{ getPermisos(item.permissions) }}
                        </template>
                        <template v-slot:item.actions="{ item }">
                            <v-icon
                                small
                                class="mr-2"
                                @click="editItem(item.id)"
                            >
                                mdi-pencil
                            </v-icon>
                        </template>
                    </v-data-table>
                </v-col>
            </v-row>
            </v-container>
        </v-card>
    </div>
</template>
<script>
import MenuOpe from './MenuOpe'
  export default {
    components: {
        'menu-ope': MenuOpe
    },
    data () {
      return {
        titulo: "Roles",
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
          {
            text: 'Permisos',
            align: 'left',
            value: 'permissions',
            sortable: false

          },
          { text: 'Guard', align: 'left', value: 'guard_name' },
          {
            text: 'Acciones',
            align: 'Center',
            value: 'actions',
            sortable: false
          }
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
                this.registros = true;
            })
    },
    methods:{

        getPermisos(per){
            if (per.length == 0) return null;
            var p = per.map(function(x) {
                    return x.nombre;
                });
            return p.toString();
        },
        editItem (id) {
            this.$router.push({ name: 'roles_edit', params: { id: id } })
        },
    }
  }
</script>
