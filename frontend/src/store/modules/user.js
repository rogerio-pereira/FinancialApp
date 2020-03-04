const state = {
    user: null,
};

const getters = {
    authUser: state => {
        return state.user;
    }
};

const actions = {
    fetchAuthUser({commit}) {
        this.$http.get('/api/auth-user')
            .then(response => {
                commit('setAuthUser', response.data);
            }) 
            .catch(error => {
                console.log('Unable to fetch auth user.\n'+error);
            });
    }
};

const mutations = {
    setAuthUser(state, user) {
        state.user = user;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
}