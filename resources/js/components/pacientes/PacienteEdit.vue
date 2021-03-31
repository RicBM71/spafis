<template>
    <div v-show="!show_loading">
        <loading :show_loading="show_loading"></loading>
        <v-card>
            <v-card-title>
                <h2 v-if="paciente.fecha_baja == null">{{titulo}}</h2>
                <h2 v-else><span class="red--text darken-4">Borrado {{titulo}}</span></h2>
                <v-spacer></v-spacer>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn
                            :disabled="!hasContact"
                            v-on="on"
                            color="white"
                            icon
                            @click="goCreateBono()"
                        >
                            <v-icon color="primary">mdi-alpha-b-circle-outline</v-icon>
                        </v-btn>
                    </template>
                    <span>Nuevo Bono</span>
                </v-tooltip>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn
                            :disabled="!hasClinic"
                            v-on="on"
                            color="white"
                            icon
                            @click="goCreateHistoria"
                        >
                            <v-icon color="green">mdi-hospital-building</v-icon>
                        </v-btn>
                    </template>
                    <span>Nueva entrada en historia clínica</span>
                </v-tooltip>
                <menu-ope :id="paciente.id" :foto="paciente.foto" :recomendado_id="paciente.recomendado_id"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-container>
                <v-tabs fixed-tabs color="primary">
                    <v-tab>
                        Datos generales
                    </v-tab>
                    <v-tab :disabled="!hasClinic">
                        Historia Clínica
                    </v-tab>
                    <v-tab :disabled="!hasClinic">
                        Documentos
                    </v-tab>
                    <v-tab>
                        Histórico Citas
                    </v-tab>
                    <v-tab v-if="isSupervisor">
                        Bonos
                    </v-tab>
                    <v-tab v-if="isSupervisor">
                        Facturas
                    </v-tab>

                    <v-tab-item>
                        <v-card flat>
                            <v-card-text>

                                 <v-form>

                                <v-row>
                                    <v-col
                                        cols="12"
                                        md="2"
                                    >
                                        <v-text-field
                                            v-model="paciente.nombre"
                                            v-validate="'required'"
                                            :error-messages="errors.collect('nombre')"
                                            label="Nombre"
                                            data-vv-name="nombre"
                                            data-vv-as="nombre"
                                            v-on:keyup.enter="submit"
                                            required
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        md="3"
                                    >
                                        <v-text-field
                                            v-model="paciente.apellidos"
                                            v-validate="'required'"
                                            :error-messages="errors.collect('apellidos')"
                                            label="Apellidos"
                                            data-vv-name="apellidos"
                                            data-vv-as="apellidos"
                                            v-on:keyup.enter="submit"
                                            required
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        md="2"
                                    >
                                    <!-- <v-menu
                                        ref="menu"
                                        v-model="menu"
                                        :close-on-content-click="false"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="auto"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="computedFN"
                                            label="F. Nacimiento"
                                            prepend-icon="mdi-calendar"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                        </template>
                                        <v-date-picker
                                                locale="es"
                                                ref="picker"
                                                v-model="paciente.fecha_nacimiento"
                                                :max="new Date().toISOString().substr(0, 10)"
                                                min="1900-01-01"
                                                @change="save"
                                            ></v-date-picker>
                                    </v-menu> -->
                                        <v-text-field
                                            v-model="computedFechaNac"
                                            mask="##/##/####"
                                            clearable
                                            @click:clear="clearFechaNac"
                                            :error-messages="errors.collect('fecha_nacimiento')"
                                            label="F. Nacimiento"
                                            data-vv-name="fecha_nac"
                                            v-on:keyup.enter="submit"                                    >
                                        </v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        md="1"
                                    >
                                        <v-text-field
                                            v-model="paciente.edad"
                                            label="Edad"
                                            readonly
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        md="2"
                                        v-show="hasContact"
                                    >
                                        <v-text-field
                                            v-model="paciente.cif"
                                            v-validate="'alpha_dash'"
                                            :error-messages="errors.collect('cif')"
                                            label="DNI/NIE"
                                            data-vv-name="cif"
                                            data-vv-as="cif"
                                            v-on:keyup.enter="submit"
                                            required
                                        >
                                        </v-text-field>
                                    </v-col>
                                     <v-col
                                        cols="12"
                                        md="1"
                                    >
                                     </v-col>
                                     <v-col
                                        cols="12"
                                        md="1"
                                    >
                                        <v-avatar size="64px" v-if="paciente.foto!=false">
                                            <img class="img-fluid" :src="paciente.foto">
                                        </v-avatar>
                                    </v-col>
                                </v-row>
                                <v-row v-show="hasContact">
                                    <v-col
                                        cols="12"
                                        md="3"
                                    >
                                        <v-text-field
                                            v-model="paciente.direccion"
                                            v-validate="'max:50'"
                                            :error-messages="errors.collect('direccion')"
                                            label="Dirección"
                                            data-vv-name="direccion"
                                            data-vv-as="direccion"
                                            v-on:keyup.enter="submit"
                                            required
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        md="1"
                                    >
                                        <v-text-field
                                            v-model="paciente.cpostal"
                                            v-validate="'max:5'"
                                            :error-messages="errors.collect('cpostal')"
                                            label="CP"
                                            data-vv-name="cpostal"
                                            data-vv-as="cpostal"
                                            v-on:keyup.enter="submit"
                                            required
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        md="3"
                                    >
                                        <v-text-field
                                            v-model="paciente.poblacion"
                                            v-validate="'max:50'"
                                            :error-messages="errors.collect('poblacion')"
                                            label="Poblacion"
                                            data-vv-name="poblacion"
                                            data-vv-as="poblacion"
                                            v-on:keyup.enter="submit"
                                            required
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        md="2"
                                    >
                                        <v-text-field
                                            v-model="paciente.provincia"
                                            v-validate="'max:50'"
                                            :error-messages="errors.collect('provincia')"
                                            label="Provincia"
                                            data-vv-name="provincia"
                                            data-vv-as="provincia"
                                            v-on:keyup.enter="submit"
                                            required
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        md="2"
                                    >
                                        <v-select
                                            v-model="paciente.sexo"
                                            v-validate="'required'"
                                            :error-messages="errors.collect('sexo')"
                                            label="Sexo"
                                            data-vv-name="sexo"
                                            data-vv-as="sexo"
                                            :items="sexos"
                                        ></v-select>
                                    </v-col>

                                </v-row>
                                <v-row v-show="hasContact">
                                    <v-col
                                        cols="12"
                                        md="1"
                                    >
                                        <v-text-field
                                            v-model="paciente.telefonom"
                                            v-validate="'numeric|max:10'"
                                            :error-messages="errors.collect('telefonom')"
                                            label="Móvil"
                                            data-vv-name="telefonom"
                                            data-vv-as="telefonom"
                                            v-on:keyup.enter="submit"
                                            required
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        md="1"
                                    >
                                        <v-text-field
                                            v-model="paciente.telefono1"
                                            v-validate="'numeric|max:10'"
                                            :error-messages="errors.collect('telefono1')"
                                            label="Teléfono"
                                            data-vv-name="telefono1"
                                            data-vv-as="telefono1"
                                            v-on:keyup.enter="submit"

                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        md="2"
                                    >
                                        <v-text-field
                                            v-model="paciente.texto_tf2"
                                            v-validate="'max:50'"
                                            :error-messages="errors.collect('texto_tf2')"
                                            label="Contacto"
                                            data-vv-name="texto_tf2"
                                            data-vv-as="contacto"
                                            v-on:keyup.enter="submit"
                                            required
                                        >
                                        </v-text-field>
                                    </v-col>

                                    <v-col
                                        cols="12"
                                        md="1"
                                    >
                                        <v-text-field
                                            v-model="paciente.telefono2"
                                            v-validate="'numeric|max:10'"
                                            :error-messages="errors.collect('telefono2')"
                                            label="Teléfono 2"
                                            data-vv-name="telefono2"
                                            data-vv-as="telefono2"
                                            v-on:keyup.enter="submit"

                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        md="3"
                                    >
                                        <v-text-field
                                            v-model="paciente.email"
                                            v-validate="'email'"
                                            :error-messages="errors.collect('email')"
                                            label="email"
                                            data-vv-name="email"
                                            data-vv-as="email"
                                            v-on:keyup.enter="submit"
                                            required
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        md="3"
                                    >
                                        <v-text-field
                                            v-model="paciente.profesion"
                                            v-validate="'max:50'"
                                            :error-messages="errors.collect('profesion')"
                                            label="Profesión"
                                            data-vv-name="profesion"
                                            data-vv-as="profesion"
                                            v-on:keyup.enter="submit"
                                            required
                                        >
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row v-show="hasContact">
                                    <v-col
                                        cols="12"
                                        md="2"
                                    >
                                        <v-select
                                            v-model="paciente.medio_id"
                                            v-validate="'numeric'"
                                            :error-messages="errors.collect('medio_id')"
                                            label="Medio"
                                            data-vv-name="medio_id"
                                            data-vv-as="medio_id"
                                            :items="medios"
                                        ></v-select>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        md="3"
                                    >
                                        <v-select
                                            v-model="paciente.mutua_id"
                                            v-validate="'numeric'"
                                            :error-messages="errors.collect('mutua_id')"
                                            label="Sociedad"
                                            data-vv-name="mutua_id"
                                            data-vv-as="mutua_id"
                                            :items="mutuas"
                                        ></v-select>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        md="3"
                                    >
                                        <v-autocomplete
                                            v-model="paciente.recomendado_id"
                                            :items="recomendados"
                                            :loading="isLoading"
                                            :search-input.sync="search"
                                            label="Recomendado"
                                            placeholder="Indicar nom,ape"
                                            prepend-icon="mdi-database-search"
                                            clearable
                                            @click:clear="clearRec"
                                        ></v-autocomplete>
                                    </v-col>
                                    <v-col cols="12" md="2">
                                        <v-switch
                                            label="Lortad Firmado"
                                            v-model="paciente.lortad_evo"
                                            color="warning">
                                        ></v-switch>
                                    </v-col>
                                    <v-col cols="12" md="2">
                                        <v-switch
                                            label="Lortad Fidelizar"
                                            v-model="paciente.lortad_fide"
                                            color="warning">
                                        ></v-switch>
                                    </v-col>
                                </v-row>
                                <v-row v-show="hasContact">
                                    <v-col
                                        cols="12"
                                        sm="1"
                                    >
                                        <v-select
                                            v-model="paciente.notificar"
                                            :error-messages="errors.collect('notificar')"
                                            label="Notificar"
                                            data-vv-name="notificar"
                                            data-vv-as="notificar"
                                            :items="notificados"
                                        ></v-select>

                                    </v-col>
                                    <v-col cols="12" md="2">
                                        <v-switch
                                            label="Lista Espera"
                                            v-model="paciente.espera"
                                            color="error">
                                        ></v-switch>
                                    </v-col>
                                    <v-col cols="12" md="2">
                                        <v-switch
                                            label="Tarifa Red."
                                            v-model="paciente.tarifa_reducida"
                                            color="primary">
                                        ></v-switch>
                                    </v-col>
                                    <v-col cols="12" md="2">
                                        <v-switch
                                            label="Descuento %"
                                            v-model="paciente.porcentual"
                                            color="primary">
                                        ></v-switch>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        md="1"
                                    >
                                        <v-text-field
                                            v-model="paciente.descuento"
                                            v-validate="'required'"
                                            class="inputNumber"
                                            :error-messages="errors.collect('descuento')"
                                            label="Descuento"
                                            data-vv-name="descuento"
                                            data-vv-as="descuento"
                                            v-on:keyup.enter="submit"
                                            required
                                        >
                                        </v-text-field>
                                    </v-col>

                                    <v-col cols="12" md="2">
                                        <v-switch
                                            label="Exportar"
                                            v-model="paciente.exportar"
                                            color="primary">
                                        ></v-switch>
                                    </v-col>
                                     <v-col cols="12" md="2">
                                        <v-switch
                                            label="Fact. Auto"
                                            v-model="paciente.factura_auto"
                                            color="primary">
                                        ></v-switch>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" md="6"  v-show="hasContact">
                                        <v-text-field
                                            v-model="paciente.notas1"
                                            label="Observaciones Pantalla"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-text-field
                                            v-model="paciente.notas2"
                                            label="Observaciones Lista"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" md="4" v-if="isAdmin">
                                        <v-text-field
                                            background-color="red lighten-5"
                                            v-model="paciente.notas_adm"
                                            label="Observaciones Admin"
                                            v-on:keyup.enter="submit"
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="4" v-else></v-col>
                                    <v-col cols="12" md="2">
                                        <v-text-field
                                            v-show="isAdmin"
                                            v-model="paciente.username"
                                            label="Usuario"
                                            readonly
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="2">
                                        <v-text-field
                                            v-model="computedFModFormat"
                                            label="Modificado"
                                            readonly
                                        >
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="2">
                                        <v-text-field
                                            v-show="isAdmin"
                                            v-model="computedFCreFormat"
                                            label="Creado"
                                            readonly
                                        >
                                        </v-text-field>
                                    </v-col>

                                    <v-col
                                        cols="12"
                                        md="2"
                                    >
                                        <v-btn v-if="paciente.fecha_baja == null" @click="submit"  rounded  :loading="loading" small color="primary">
                                            Guardar
                                        </v-btn>
                                    </v-col>
                                </v-row>


                        </v-form>

                            </v-card-text>
                        </v-card>

                    </v-tab-item>
                    <v-tab-item>
                        <v-card flat>
                            <v-card-text>
                                <v-form>
                                    <v-row>
                                        <v-col cols="12" md="6">
                                            <v-text-field
                                                class="font-weight-medium"
                                                v-model="paciente.ant1"
                                                label="Antecedentes"
                                                hint="Hepatitis, Fibromialgia, VIH,Cáncer, E. Chron..."
                                                v-on:keyup.enter="submit"
                                            >
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="6">
                                            <v-text-field
                                                class="font-weight-medium"
                                                v-model="paciente.ant2"
                                                label="Implantes"
                                                hint="DIU, Osteosíntesis, Prótesis..."
                                                v-on:keyup.enter="submit"
                                            >
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="6">
                                            <v-text-field
                                                class="font-weight-medium"
                                                v-model="paciente.ant3"
                                                label="Lesiones Oseas"
                                                hint="Hepatitis, Fibromialgia, VIH,Cáncer, E. Chron..."
                                                v-on:keyup.enter="submit"
                                            >
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="6">
                                            <v-text-field
                                                class="font-weight-medium"
                                                v-model="paciente.ant4"
                                                label="Enf. Cardiovasculares"
                                                hint="DIU, Osteosíntesis, Prótesis..."
                                                v-on:keyup.enter="submit"
                                            >
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="6">
                                            <v-text-field
                                                class="font-weight-medium"
                                                v-model="paciente.ant5"
                                                label="Lesiones Cutaneas"
                                                hint="Alergia, soriasis, cicatrices..."
                                                v-on:keyup.enter="submit"
                                            >
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="6">
                                            <v-text-field
                                                class="font-weight-medium"
                                                v-model="paciente.ant6"
                                                label="Otras lesiones"
                                                v-on:keyup.enter="submit"
                                            >
                                            </v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12" md="6">
                                            <v-text-field
                                                class="font-weight-medium"
                                                v-model="paciente.antobs"
                                                label="Observaciones"
                                                v-on:keyup.enter="submit"
                                            >
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="1">
                                            <v-text-field
                                                class="font-weight-medium"
                                                v-model="paciente.peso"
                                                label="Peso"
                                                v-on:keyup.enter="submit"
                                            >
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="1">
                                            <v-text-field
                                                class="font-weight-medium"
                                                v-model="paciente.altura"
                                                label="Altura"
                                                v-on:keyup.enter="submit"
                                            >
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="1">
                                            <v-switch
                                                label="Embarazada"
                                                v-model="paciente.embarazada"
                                                color="warning">
                                            ></v-switch>
                                        </v-col>
                                        <v-col
                                                cols="12"
                                                md="1"
                                            >
                                        </v-col>
                                        <v-col
                                                cols="12"
                                                md="1"
                                            >
                                                <v-btn v-if="paciente.fecha_baja == null" @click="submit"  rounded  :loading="loading" small color="primary">
                                                    Guardar
                                                </v-btn>
                                            </v-col>
                                    </v-row>
                                </v-form>
                                <v-row>
                                    <v-col cols="12" md="12">
                                        <historias :historias="historias"></historias>
                                    </v-col>
                                </v-row>

                            </v-card-text>
                        </v-card>

                    </v-tab-item>
                    <v-tab-item>
                        <v-card flat>
                            <v-card-text>
                                <v-row>
                                    <v-col cols="12" md="12">
                                        <adjuntos :adjuntos="adjuntos"></adjuntos>
                                    </v-col>
                                </v-row>
                                <v-row v-if="isSupervisor">
                                    <v-col cols="12" md="4"></v-col>
                                    <v-col cols="12" md="3">
                                        <vue-dropzone
                                                    ref="myVueDropzone"
                                                    id="dropzone"
                                                    :options="dropzoneOptions"
                                                    v-on:vdropzone-success="uploadAdjunto"
                                        ></vue-dropzone>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>

                    </v-tab-item>
                    <v-tab-item>
                        <v-card flat>
                            <v-card-text>
                                <v-row>
                                    <v-col cols="12" md="12">
                                        <citas-lin v-if="isSupervisor" :citas="citas"></citas-lin>
                                        <citas-linred v-else :citas="citas"></citas-linred>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                    <v-tab-item>
                        <v-card flat>
                            <v-card-text>
                                <v-row>
                                    <v-col cols="12" md="12">
                                        <pacbonos :pacbonos="pacbonos"></pacbonos>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                    <v-tab-item>
                        <v-card flat>
                            <v-card-text>
                                <v-row>
                                    <v-col cols="12" md="12">
                                        <facturas-lin :facturas="facturas"></facturas-lin>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                </v-tabs>
            </v-container>
        </v-card>
	</div>
</template>
<script>
import moment from 'moment'
import Loading from '@/components/shared/Loading'
import MenuOpe from './MenuOpe'
import Historias from './historias/Historias'
import Pacbonos from './pacbono/Pacbonos'
import Adjuntos from './adjuntos/Adjuntos'
import FacturasLin from './facturas/FacturasLin'
import CitasLin from './citas/CitasLin'
import CitasLinRed from './citas/CitasLinRed'
import {mapGetters} from 'vuex';
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
	export default {
		$_veeValidate: {
      		validator: 'new'
        },
        components: {
            'menu-ope': MenuOpe,
            'loading': Loading,
            'historias': Historias,
            'pacbonos': Pacbonos,
            'adjuntos': Adjuntos,
            'facturas-lin': FacturasLin,
            'citas-lin': CitasLin,
            'citas-linred': CitasLinRed,
            'vueDropzone': vue2Dropzone,
		},
    	data () {
      		return {
                titulo:"Editar",
                paciente: {foto:false},
                sexos:[
                    {value: 'V', text:"Varón"},
                    {value: 'M', text:"Mujer"},
                ],
                notificados:[
                    {value: 'N', text:"No"},
                    {value: 'S', text:"SMS"},
                    {value: 'M', text:"Mail"},
                ],
                url: "/mto/pacientes",
                ruta: "paciente",
                loading: false,
                menu1: false,
                menu: false,

                medios:[],
                mutuas:[],

                historias:[],
                pacbonos:[],
                adjuntos:[],
                citas:[],
                facturas:[],

                show: false,
                show_loading: true,

                recomendados:[],
                isLoading: false,
                search: null,
                model: null,


                dropzoneOptions: {
                    url: '/mto/adjuntos/'+this.$route.params.id+'/upload',
                    paramName: 'adjunto',
                    acceptedFiles: '.jpg,jpeg,.png,.pdf',
                    thumbnailWidth: 150,
                    maxFiles: 1,
                    maxFilesize: 4,
                    headers: {
		    		    'X-CSRF-TOKEN':  window.axios.defaults.headers.common['X-CSRF-TOKEN']
                    },
                    dictDefaultMessage: 'Arrastra el fichero/documento o clic aquí'
                },


      		}
        },
        mounted(){
            var id = this.$route.params.id;

            if (id > 0)
                axios.get(this.url+'/'+id+'/edit')
                    .then(res => {

                        this.paciente = res.data.paciente;
                        this.mutuas = res.data.mutuas;
                        this.medios = res.data.medios;

                        this.historias = this.paciente.historias;
                        this.pacbonos = this.paciente.pacbonos;
                        this.adjuntos = this.paciente.adjuntos;
                        this.facturas = this.paciente.facturas;
                        this.citas = res.data.citas;

                        this.titulo = res.data.paciente.nom_ape;

                        if (res.data.recomendado != null)
                            this.recomendados = res.data.recomendado;

                        this.mutuas.push({value: null, text: '-'});
                        this.medios.push({value: null, text: '-'});

                        this.show = true;
                        this.show_loading = false;
                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'dash'})
                    })
                    .finally(()=> {
                        this.show_loading = false;
                    });
        },
        computed: {
        ...mapGetters([
                'isAdmin',
                'isSupervisor',
                'hasClinic',
                'hasContact'
            ]),
            computedFechaNac: {
                // getter
                get: function () {
                  moment.locale('es');
                    return this.paciente.fecha_nacimiento ? moment(this.paciente.fecha_nacimiento).format('DD/MM/YYYY') : '';
                },
                // setter
                set: function (newValue) {

                    if ( newValue != null && newValue.length == 8 ){
                            var f = newValue.substring(4,8)+"-"+
                                    newValue.substring(2,4)+"-"+
                                    newValue.substring(0,2);

                        this.paciente.fecha_nacimiento = f;
                    }
                }
            },
            computedFN() {
                moment.locale('es');
                return this.paciente.fecha_nacimiento ? moment(this.paciente.fecha_nacimiento).format('L') : '';
            },
            // computedFecha() {
            //     moment.locale('es');
            //     return this.paciente.fecha_nacimiento ? moment(this.paciente.fecha_nacimiento).format('L') : '';
            // },
            computedFModFormat() {
                moment.locale('es');
                return this.paciente.updated_at ? moment(this.paciente.updated_at).format('DD/MM/YYYY H:mm') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.paciente.created_at ? moment(this.paciente.created_at).format('DD/MM/YYYY H:mm') : '';
            }

        },
        created: function () {
            this.debouncedGetAnswer = _.debounce(this.getPacientes, 1000)
        },
        watch: {
            menu (val) {
                val && setTimeout(() => (this.$refs.picker.activePicker = 'YEAR'))
            },
            search: function (newQuestion, oldQuestion) {
                this.debouncedGetAnswer()
            },
        },
    	methods:{
            getPacientes (val) {
                //console.log(val);
                //this.recomendados = [];
                // Items have already been loaded
               //if (this.recomendados.length == 0) return;

                if (this.search == null || this.search.length <= 4) return;

                // Items have already been requested
                if (this.isLoading) return;

                this.isLoading = true;


                 axios.post('/tools/helpcli', {search: this.search})
                    .then(res => {
                        //console.log(res.data);
                        if (res.data.length > 0){
                            this.recomendados = res.data;
                            this.paciente.recomendado_id = this.recomendados[0].value;

                        }

                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: this.ruta+'.index'})
                    })
                    .finally(() => (
                        this.isLoading = false
                    ));


            },
            save (date) {
                this.$refs.menu.save(date)
            },
            clearRec(){
                this.paciente.recomendado_id = null;
                this.recomendados = [];
            },
            submit() {

                if (this.loading === false){
                    this.loading = true;

                    this.$validator.validateAll().then((result) => {
                        if (result){
                             axios.put(this.url+"/"+this.paciente.id, this.paciente)
                                .then(res => {

                                    this.$toast.success(res.data.message);
                                    this.paciente = res.data.paciente;

                                    //this.$validator.reset();

                                    this.errors.clear();
                                })
                                .catch(err => {

                                    if (err.request.status == 422){ // fallo de validated.
                                        const msg_valid = err.response.data.errors;
                                        for (const prop in msg_valid) {
                                            // this.$toast.error(`${msg_valid[prop]}`);

                                            this.errors.add({
                                                field: prop,
                                                msg: `${msg_valid[prop]}`
                                            })
                                        }
                                    }else{
                                        this.$toast.error(err.response.data.message);
                                    }
                                })
                                .finally(()=> {

                                    this.loading = false;
                                    this.show_loading = false;
                                });
                            }
                        else{
                            this.loading = false;
                        }
                    });
                }

            },
            clearFechaNac(){
                this.paciente.fecha_nacimiento = null;

            },
            goCreateHistoria(){

                this.$router.push({ name: 'historia.create', params: {paciente_id: this.paciente.id} })

                // this.show_loading = true;
                // axios.post('/mto/historias', {paciente_id: this.paciente.id})
                //     .then(response => {
                //         this.$toast.success('Se ha creado una nueva entrada');
                //         this.$router.push({ name: 'historia.edit', params: { id: response.data.historia.id } })
                //     })
                //     .catch(err => {
                //         this.$toast.error(err.response.data.message);
                //     });

            },
            goCreateBono(){
              this.$router.push({ name: 'pacbono.create', params: { id: this.paciente.id } })
            },
            uploadAdjunto(file, response){
                this.adjuntos = response.adjuntos;
                this.$refs['myVueDropzone'].removeAllFiles(true);
                this.$toast.success('Se ha subido el fichero correctamente');
                //this.empresa = response.empresa;
            },

    }
  }
</script>
<style scoped>

.inputNumber >>> input {
  text-align: center;
  -moz-appearance:textfield;
}


.v-form>.container>.layout>.flex {
    padding: 1px 0px 1px 0px;
}
.v-text-field {
    padding-top: 1px;
    margin-top: 1px;
}
</style>
