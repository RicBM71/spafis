    <v-app id="inspire">
          <loading :show_loading="show_loading"></loading>
          <div class="text-xs-center">
            <v-dialog
            v-model="myEmpresa"
            width="500"
            >
            <v-card>
                <v-card-title
                class="headline grey lighten-2"
                primary-title
                >
                Seleccionar empresa
                </v-card-title>

                <v-card-text>
                    <v-flex sm2 d-flex></v-flex>
                    <v-flex sm8 d-flex>
                        <v-select
                            v-on:change="setEmpresa"
                            v-model="empresa_id"
                            :items="empresas"
                            label="Empresa"
                        ></v-select>
                    </v-flex>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="primary"
                    round
                    flat
                    @click="myEmpresa=false"
                >
                    Cerrar
                </v-btn>
                </v-card-actions>
            </v-card>
            </v-dialog>
        </div>
        <div v-if="isLoggedIn">
            <v-navigation-drawer
                v-model="drawer"
                :clipped="$vuetify.breakpoint.lgAndUp"
                app
                >
                <v-list dense>
                    <template v-for="item in items">
                    <v-row
                        v-if="item.heading"
                        :key="item.heading"
                        align="center"
                    >
                        <v-col cols="6">
                        <v-subheader v-if="item.heading">
                            {{ item.heading }}
                        </v-subheader>
                        </v-col>
                        <v-col
                        cols="6"
                        class="text-center"
                        >
                        <a
                            href="#!"
                            class="body-2 black--text"
                        >EDIT</a>
                        </v-col>
                    </v-row>
                    <v-list-group
                        v-else-if="item.children"
                        :key="item.text"
                        v-model="item.model"
                        :prepend-icon="item.model ? item.icon : item['icon-alt']"
                        append-icon=""
                    >
                        <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title>
                            {{ item.text }}
                            </v-list-item-title>
                        </v-list-item-content>
                        </template>
                        <v-list-item
                        v-for="(child, i) in item.children"
                        :key="i"
                        link
                        >
                        <v-list-item-action v-if="child.icon">
                            <v-icon>{{ child.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>
                            {{ child.text }}
                            </v-list-item-title>
                        </v-list-item-content>
                        </v-list-item>
                    </v-list-group>
                    <v-list-item
                        v-else
                        :key="item.text"
                        link
                    >
                        <v-list-item-action>
                        <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                        <v-list-item-title>
                            {{ item.text }}
                        </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    </template>
                </v-list>
                </v-navigation-drawer>
            <!-- <v-navigation-drawer
                v-if="isLoggedIn"
                v-model="drawer"
                :clipped="$vuetify.breakpoint.lgAndUp"
                app
                >
                <v-list dense>
                    <template v-for="item in mn_items">
                    <v-row
                        v-if="item.heading"
                        :key="item.heading"
                        align-center
                    >
                        <v-col cols="6">
                            <v-subheader v-if="item.heading">
                                {{ item.heading }}head
                            </v-subheader>
                        </v-col>
                        <v-col cols="6" class="text-xs-center">
                        <a href="#!" class="body-2 black--text">EDIT</a>
                        </v-col>
                    </v-row>
                    <v-list-group
                        v-else-if="item.children"
                        :key="item.text"
                        v-model="item.model"
                        :prepend-icon="item.model ? item.icon : item['icon-alt']"
                        append-icon=""
                    >
                     <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title>
                            {{ item.text }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </template>

                        <v-list-item
                        v-for="child in item.children"
                        :key="child.name"
                        :to="{ name: child.name}"
                        >
                        <v-list-item-action v-if="child.icon">
                            <v-icon>{{ child.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>
                            {{ child.text }}
                            </v-list-item-title>
                        </v-list-item-content>
                        </v-list-item>
                    </v-list-group>
                    <v-list-item v-else :key="item.text" @click="abrir(item.name)">
                        <v-list-item-action>
                        <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                        <v-list-item-title>
                            {{ item.text }}
                        </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    </template>
                </v-list>
            </v-navigation-drawer>            -->
            <v-app-bar
                v-if="menu"
                :clipped-left="$vuetify.breakpoint.lgAndUp"
                color="blue darken-3"
                dark

                >
                    <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
                <v-toolbar-title style="width: 300px" class="ml-0 pl-4">
                    <span class="hidden-sm-and-down">{{ this.user.empresa_nombre }}</span>
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" icon v-show="jobs > 0">
                            <v-icon color="red darken-4">mdi-bell</v-icon>
                        </v-btn>
                    </template>
                    <span>({{jobs}}) Mails pendientes de envio.</span>
                </v-tooltip>

                <v-btn icon v-on:click="empresa">
                    <v-icon>mdi-briefcase</v-icon>
                </v-btn>
                <v-btn icon v-on:click="home">
                    <v-icon>mdi-home</v-icon>
                </v-btn>
                <v-btn icon v-on:click="passwd">
                    <v-avatar v-if="user.avatar !='#'" size="32px">
                        <img class="img-fluid" :src="user.avatar">
                    </v-avatar>
                    <v-icon v-else>mdi-settings</v-icon>
                </v-btn>
                <strong v-html="user.name"></strong>
                <v-btn icon large  v-on:click="Logout">
                    <v-avatar size="32px" tile>
                        <v-icon>mdi-exit_to_app</v-icon>
                    </v-avatar>
                </v-btn>
            </v-app-bar>
            <v-main>


                    <router-view :key="$route.fullPath"></router-view>


            </v-main>
        </div>
</v-app>
