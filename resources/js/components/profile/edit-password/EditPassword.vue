<template>
	<div>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">Actualizar Perfil</h2>
                <v-spacer></v-spacer>
            </v-card-title>
        </v-card>
        <v-card>
            <v-container>
                <v-row>
                    <v-col><h3>{{userName}}</h3>
                    </v-col>
                </v-row>
            </v-container>
			<edit-password-form @updateSuccess="updateSuccess"></edit-password-form>
        </v-card>
	</div>
</template>

<script>
import EditPasswordForm from './EditPasswordForm.vue'
import {mapGetters} from 'vuex';
	export default {
		components: {
			'edit-password-form': EditPasswordForm
        },
        computed: {
            ...mapGetters([
                'userName'
            ]),
        },
		methods: {
			updateSuccess() {
				this.$router.push({name: 'dash'});
			}
		}
	}
</script>
