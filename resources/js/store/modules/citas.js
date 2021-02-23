/*
|--------------------------------------------------------------------------
| Mutation Types
|--------------------------------------------------------------------------
*/
export const SET_CITA = 'SET_CITA';
export const UNSET_CITA = 'UNSET_CITA';

/*
|--------------------------------------------------------------------------
| Initial State
|--------------------------------------------------------------------------
*/
const initialState = {
    id: null,
    fecha: null,
    facultativo_id: null,
    type: null,
};

/*
|--------------------------------------------------------------------------
| Mutations
|--------------------------------------------------------------------------
*/
const mutations = {
	[SET_CITA](state, payload) {
        state.id = payload.cita.id;
        state.fecha = payload.cita.fecha;
        state.facultativo_id = payload.cita.facultativo_id;
        state.type = payload.cita.type;
	},
	[UNSET_CITA](state, payload) {
        state.id = null;
        state.facultativo_id=null;
        state.type=null;
        state.fecha=null;
	}
};

/*
|--------------------------------------------------------------------------
| Actions
|--------------------------------------------------------------------------
*/
const actions = {
	setCita: (context, cita) => {
		context.commit(SET_CITA, {cita})
	},
	unsetCita: (context) => {
		context.commit(UNSET_CITA);
	}
};

/*
|--------------------------------------------------------------------------
| Getters
|--------------------------------------------------------------------------
*/
const getters = {
    getUltCita:(state) =>{
        return {id: state.id,
                facultativo_id: state.facultativo_id,
                type: state.type,
                fecha: state.fecha}
    },
    citaId: (state) =>{
        return state.id
    },
    getFisio: (state) =>{
        return state.facultativo_id
    },
    getType: (state) =>{
        return state.type
    },
    getFecha: (state) =>{
        return state.fecha
    },
};

/*
|--------------------------------------------------------------------------
| Export the module
|--------------------------------------------------------------------------
*/
export default {
	state: initialState,
	mutations,
	actions,
	getters
}
