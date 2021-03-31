/*
|--------------------------------------------------------------------------
| Mutation Types
|--------------------------------------------------------------------------
*/
export const SET_PAGINATION = 'SET_PAGINATION';
export const UNSET_PAGINATION = 'UNSET_PAGINATION';

/*
|--------------------------------------------------------------------------
| Initial State
|--------------------------------------------------------------------------
*/
const initialState = {
    model: null,
    filtro: false,
    options: {},
};

/*
|--------------------------------------------------------------------------
| Mutations
|--------------------------------------------------------------------------
*/
const mutations = {
	[SET_PAGINATION](state, payload) {
        state.model = payload.pagination.model;
        state.filtro = payload.pagination.filtro;
        state.options = payload.pagination.options;
	},
	[UNSET_PAGINATION](state, payload) {
        state.model = null;
        state.filtro = false;
        state.options = {};
	}
};

/*
|--------------------------------------------------------------------------
| Actions
|--------------------------------------------------------------------------
*/
const actions = {
	setPagination: (context, pagination) => {
		context.commit(SET_PAGINATION, {pagination})
	},
	unsetPagination: (context) => {
		context.commit(UNSET_PAGINATION);
	}
};

/*
|--------------------------------------------------------------------------
| Getters
|--------------------------------------------------------------------------
*/
const getters = {
    getPagination: (state) =>{
        return state
    }

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
