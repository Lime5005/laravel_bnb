export default {
    state: {
        lastSearch: {
            from: null,
            to: null
        },
        shoppingcart: {
            items: [] // bookable, price, dates, these are defined in `addToShoppingcart()` method in `Bookable.vue`
        }
    },
    mutations: {
        setLastSearch(state, payload) {
            state.lastSearch = payload
        },
        addToShoppingcart(state, payload) {
            state.shoppingcart.items.push(payload)
        },
        removeFromShoppingcart(state, payload) {
            state.shoppingcart.items = state.shoppingcart.items.filter(item => item.bookable.id !== payload)
        },
        setShoppingcart(state, payload) {
            state.shoppingcart = payload // For reading the state
        }
    },
    actions: {
        setLastSearch(context, payload) {
            context.commit('setLastSearch', payload)
            localStorage.setItem('lastSearch', JSON.stringify(payload))
        },
        loadStoredState(context) {
            const lastSearch = localStorage.getItem('lastSearch')
            if (lastSearch) {
                context.commit('setLastSearch', JSON.parse(lastSearch))
            }

            const shoppingcart = localStorage.getItem('shoppingcart')
            if (shoppingcart) {
                context.commit('setShoppingcart', JSON.parse(shoppingcart))
            }
        },
        addToShoppingcart({ commit, state }, payload) {
            // { commit, state } = context.commit, context.state
            // The state here means the whole state properties
            commit('addToShoppingcart', payload)
            localStorage.setItem('shoppingcart', JSON.stringify(state.shoppingcart))
        },
        removeFromShoppingcart({ commit, state }, payload) {
            commit('removeFromShoppingcart', payload)
            localStorage.setItem('shoppingcart', JSON.stringify(state.shoppingcart))
        },
        clearShoppingcart({ commit, state }, payload) {
            commit('setShoppingcart', { items: [] })
            localStorage.setItem('shoppingcart', JSON.stringify(state.shoppingcart))
        }
    },
    getters: {
        itemsInShoppingcart: (state) => state.shoppingcart.items.length,

        inShoppingcartAlready(state) {
            return function(id) {
                return state.shoppingcart.items.reduce((result, item) => result || item.bookable.id === id, false)
            }
        }
    }
};