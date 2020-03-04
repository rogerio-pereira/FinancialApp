

export default {
    state: {
        token: null
    },
    getters: {
        token: state => {
            return state.token;
        }
    },
    mutations: {
        setToken(state, token) {
            state.token = token;
        }
    },
    actions: {

    }
}