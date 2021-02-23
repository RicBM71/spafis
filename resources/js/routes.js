import Wellcome from './components/Wellcome.vue';
import Home from './components/Home.vue';
import Main from './components/Main.vue';
import st404 from './components/shared/404.vue';

import UserIndex from './components/admin/users/UserIndex.vue';
import UserCreate from './components/admin/users/UserCreate.vue';
import UserEdit from './components/admin/users/UserEdit.vue';
import RolesIndex from './components/admin/roles/RolesIndex.vue';
import RolesCreate from './components/admin/roles/RolesCreate.vue';
import RolesEdit from './components/admin/roles/RolesEdit.vue';


import EmpresaIndex from './components/admin/empresas/EmpresaIndex.vue';
import EmpresaCreate from './components/admin/empresas/EmpresaCreate.vue';
import EmpresaEdit from './components/admin/empresas/EmpresaEdit.vue';

import AlmacenIndex from './components/mto/almacen/AlmacenIndex.vue';
import AlmacenCreate from './components/mto/almacen/AlmacenCreate.vue';
import AlmacenEdit from './components/mto/almacen/AlmacenEdit.vue';


import IvaIndex from './components/mto/iva/IvaIndex.vue';
import IvaCreate from './components/mto/iva/IvaCreate.vue';
import IvaEdit from './components/mto/iva/IvaEdit.vue';

import BonoIndex from './components/mto/bonos/BonoIndex.vue';
import BonoCreate from './components/mto/bonos/BonoCreate.vue';
import BonoEdit from './components/mto/bonos/BonoEdit.vue';

import TratamientoIndex from './components/mto/tratamientos/TratamientoIndex.vue';
import TratamientoCreate from './components/mto/tratamientos/TratamientoCreate.vue';
import TratamientoEdit from './components/mto/tratamientos/TratamientoEdit.vue';

import FacultativoIndex from './components/mto/facultativos/FacultativoIndex.vue';
import FacultativoCreate from './components/mto/facultativos/FacultativoCreate.vue';
import FacultativoEdit from './components/mto/facultativos/FacultativoEdit.vue';
import FacultativoShow from './components/mto/facultativos/FacultativoShow.vue';

import HorarioIndex from './components/mto/facultativos/HorarioIndex.vue';
import HorarioCreate from './components/mto/facultativos/HorarioCreate.vue';
import HorarioEdit from './components/mto/facultativos/HorarioEdit.vue';

import AreaIndex from './components/mto/areas/AreaIndex.vue';
import AreaCreate from './components/mto/areas/AreaCreate.vue';
import AreaEdit from './components/mto/areas/AreaEdit.vue';

import MutuaIndex from './components/mto/mutuas/MutuaIndex.vue';
import MutuaCreate from './components/mto/mutuas/MutuaCreate.vue';
import MutuaEdit from './components/mto/mutuas/MutuaEdit.vue';

import CajaIndex from './components/mto/cajas/CajaIndex.vue';
import CajaEdit from './components/mto/cajas/CajaEdit.vue';
import CajaCreate from './components/mto/cajas/CajaCreate.vue';
import CajaCierre from './components/mto/cajas/CajaCierre.vue';

import PacienteIndex from './components/pacientes/PacienteIndex.vue';
import PacienteCreate from './components/pacientes/PacienteCreate.vue';
import PacienteEdit from './components/pacientes/PacienteEdit.vue';

import HistoriaEdit from './components/pacientes/historias/HistoriaEdit.vue';
import HistoriaCreate from './components/pacientes/historias/HistoriaCreate.vue';
import PacbonoEdit from './components/pacientes/pacbono/PacbonoEdit.vue';
import PacbonoCreate from './components/pacientes/pacbono/PacbonoCreate.vue';


import FestivoIndex from './components/mto/festivos/FestivoIndex.vue';
import FestivoCreate from './components/mto/festivos/FestivoCreate.vue';
import FestivoEdit from './components/mto/festivos/FestivoEdit.vue';

import BloqueoIndex from './components/mto/bloqueos/BloqueoIndex.vue';
import BloqueoCreate from './components/mto/bloqueos/BloqueoCreate.vue';
import BloqueoEdit from './components/mto/bloqueos/BloqueoEdit.vue';

import CuentaIndex from './components/admin/cuentas/CuentaIndex.vue';
import CuentaCreate from './components/admin/cuentas/CuentaCreate.vue';
import CuentaEdit from './components/admin/cuentas/CuentaEdit.vue';

import FacturaIndex from './components/mto/facturas/FacturaIndex.vue';
import FacturaCreate from './components/mto/facturas/FacturaCreate.vue';
import FacturaEdit from './components/mto/facturas/FacturaEdit.vue';

import Facturacion from './components/procesos/Facturacion.vue';

import CobrosMes from './components/consultas/CobrosMes.vue';

import ParametroEdit from './components/admin/parametros/ParametroEdit.vue';

import Captura from './components/pacientes/Captura.vue';

import EditPassword from './components/profile/edit-password/EditPassword.vue';

import CitasIndex from './components/citas/CitasIndex.vue';
import HCitaShow from './components/hcitas/HCitaShow.vue';
import Justificante from './components/citas/Justificante.vue';
import EnviarSMS from './components/citas/EnviarSMS.vue';
import ListarCitas from './components/citas/ListarCitas.vue';

export default [
	{
		path: '/',
		name: 'index',
		component: Wellcome
    },
	{
		path: '/login',
		name: 'login'
    },
    {
		path: '/password/reset',
		name: 'password.reset'
    },
	{
        path: '/home',
        component: Home,
        children: [
            {
				path: '',
				name: 'dash',
                component: Main,
            },
            {
                path: '/parametros',
                name: 'parametros.index',
                component: ParametroEdit,
            },
            {
                path: '/users',
                name: 'users.index',
                component: UserIndex,
            },
            {
                path: '/users/create',
                name: 'users.create',
                component: UserCreate,
            },
            {
                path: '/users/:id/edit',
                name: 'users.edit',
                component: UserEdit,
            },
            {
                path: '/roles',
                name: 'roles.index',
                component: RolesIndex,
            },
            {
                path: '/roles/create',
                name: 'roles_create',
                component: RolesCreate,
            },
            {
                path: '/roles/:id/edit',
                name: 'roles_edit',
                component: RolesEdit,
            },
            {
                path: '/users/password',
                name: 'edit.password',
                component: EditPassword,
            },
            {
                path: '/empresas',
                name: 'empresa.index',
                component: EmpresaIndex,
            },
            {
                path: '/empresas/create',
                name: 'empresa.create',
                component: EmpresaCreate,
            },
            {
                path: '/empresas/:id/edit',
                name: 'empresa.edit',
                component: EmpresaEdit,
            },
            {
                path: '/almacenes',
                name: 'almacen.index',
                component: AlmacenIndex,
            },
            {
                path: '/almacenes/create',
                name: 'almacen.create',
                component: AlmacenCreate,
            },
            {
                path: '/almacenes/:id/edit',
                name: 'almacen.edit',
                component: AlmacenEdit,
            },
            {
                path: '/ivas',
                name: 'iva.index',
                component: IvaIndex,
            },
            {
                path: '/ivas/create',
                name: 'iva.create',
                component: IvaCreate,
            },
            {
                path: '/ivas/:id/edit',
                name: 'iva.edit',
                component: IvaEdit,
            },
            {
                path: '/cajas',
                name: 'caja.index',
                component: CajaIndex,
            },
            {
                path: '/cajas/create',
                name: 'caja.create',
                component: CajaCreate,
            },
            {
                path: '/caja/:id/edit',
                name: 'caja.edit',
                component: CajaEdit,
            },
            {
                path: '/cajas/cierre',
                name: 'caja.cierre',
                component: CajaCierre,
            },
            {
                path: '/bonos',
                name: 'bono.index',
                component: BonoIndex,
            },
            {
                path: '/bonos/create',
                name: 'bono.create',
                component: BonoCreate,
            },
            {
                path: '/bonos/:id/edit',
                name: 'bono.edit',
                component: BonoEdit,
            },
            {
                path: '/tratamientos',
                name: 'tratamiento.index',
                component: TratamientoIndex,
            },
            {
                path: '/tratamientos/create',
                name: 'tratamiento.create',
                component: TratamientoCreate,
            },
            {
                path: '/tratamientos/:id/edit',
                name: 'tratamiento.edit',
                component: TratamientoEdit,
            },
            {
                path: '/facultativos',
                name: 'facultativo.index',
                component: FacultativoIndex,
            },
            {
                path: '/facultativos/create',
                name: 'facultativo.create',
                component: FacultativoCreate,
            },
            {
                path: '/facultativos/:id/edit',
                name: 'facultativo.edit',
                component: FacultativoEdit,
            },
            {
                path: '/facultativos/:id',
                name: 'facultativo.show',
                component: FacultativoShow,
            },
            {
                path: '/horarios',
                name: 'horario.index',
                component: HorarioIndex,
            },
            {
                path: '/horarios/create',
                name: 'horario.create',
                component: HorarioCreate,
            },
            {
                path: '/horarios/:id/edit',
                name: 'horario.edit',
                component: HorarioEdit,
            },
            {
                path: '/areas',
                name: 'area.index',
                component: AreaIndex,
            },
            {
                path: '/areas/create',
                name: 'area.create',
                component: AreaCreate,
            },
            {
                path: '/areas/:id/edit',
                name: 'area.edit',
                component: AreaEdit,
            },
            {
                path: '/mutuas',
                name: 'mutua.index',
                component: MutuaIndex,
            },
            {
                path: '/mutuas/create',
                name: 'mutua.create',
                component: MutuaCreate,
            },
            {
                path: '/mutuas/:id/edit',
                name: 'mutua.edit',
                component: MutuaEdit,
            },
            {
                path: '/pacientes',
                name: 'paciente.index',
                component: PacienteIndex,
            },
            {
                path: '/pacientes/create',
                name: 'paciente.create',
                component: PacienteCreate,
            },
            {
                path: '/pacientes/:id/edit',
                name: 'paciente.edit',
                component: PacienteEdit,
            },
            {
                path: '/pacientes/:id/captura',
                name: 'paciente.captura',
                component: Captura,
            },
            {
                path: '/historia/create/:paciente_id',
                name: 'historia.create',
                component: HistoriaCreate,
            },
            {
                path: '/historia/:id/edit',
                name: 'historia.edit',
                component: HistoriaEdit,
            },
            {
                path: '/pacbono/:id/edit',
                name: 'pacbono.edit',
                component: PacbonoEdit,
            },
            {
                path: '/pacbono/:id/create',
                name: 'pacbono.create',
                component: PacbonoCreate,
            },
            {
                path: '/citas',
                name: 'cita.index',
                component: CitasIndex,
            },
            {
                path: '/hcita/:id',
                name: 'hcita.show',
                component: HCitaShow,
            },
            {
                path: '/justificante/:id',
                name: 'justificante.show',
                component: Justificante,
            },
            {
                path: '/citas/sms/:fecha',
                name: 'enviar.sms',
                component: EnviarSMS,
            },
            {
                path: '/citas/listas',
                name: 'citas.listas',
                component: ListarCitas,
            },
            {
                path: '/festivos',
                name: 'festivo.index',
                component: FestivoIndex,
            },
            {
                path: '/festivos/create',
                name: 'festivo.create',
                component: FestivoCreate,
            },
            {
                path: '/festivos/:id/edit',
                name: 'festivo.edit',
                component: FestivoEdit,
            },
            {
                path: '/bloqueos',
                name: 'bloqueo.index',
                component: BloqueoIndex,
            },
            {
                path: '/bloqueos/create',
                name: 'bloqueo.create',
                component: BloqueoCreate,
            },
            {
                path: '/bloqueos/:id/edit',
                name: 'bloqueo.edit',
                component: BloqueoEdit,
            },
            {
                path: '/cuentas',
                name: 'cuenta.index',
                component: CuentaIndex,
            },
            {
                path: '/cuentas/create',
                name: 'cuenta.create',
                component: CuentaCreate,
            },
            {
                path: '/cuentas/:id/edit',
                name: 'cuenta.edit',
                component: CuentaEdit,
            },
            {
                path: '/facturas',
                name: 'factura.index',
                component: FacturaIndex,
            },
            {
                path: '/facturas/create',
                name: 'factura.create',
                component: FacturaCreate,
            },
            {
                path: '/facturas/:id/edit',
                name: 'factura.edit',
                component: FacturaEdit,
            },
            {
                path: '/facturacion',
                name: 'facturacion.index',
                component: Facturacion,
            },
            {
                path: '/cobrosmes',
                name: 'cobrosmes.index',
                component: CobrosMes,
            },

            // {
            //     path: '*',
            //     redirect: {
            //         name: 'index'
            //     }
            // }
        ]
    },
    {
        path: '*',
        name: '404',
        component: st404,
    }
];
