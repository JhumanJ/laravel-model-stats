import Vue from 'vue'

export const namespaced = true

// state
export const state = {
    currentId: null,
    content: [],
    loading: false,

    // Dates
    fromDate: null,
    toDate: null,
}

// getters
export const getters = {
    getById: (state) => (id) => {
        if (state.content.length === 0) return null
        return state.content.find(item => item.id === id)
    },
    current: (state, getters) => {
        if (state.currentId === null) return null;
        return getters.getById(state.currentId)
    }
}

// mutations
export const mutations = {
    set(state, items) {
        state.content = items
    },
    setCurrentId(state, id) {
        state.currentId = id
    },
    addOrUpdate(state, item) {
        state.content = state.content.filter((val) => val.id !== item.id)
        state.content.push(item)
        if (state.currentId === null && state.content.length) state.currentId = state.content[0].id
    },
    remove(state, item) {
        if (item.id === state.currentId) state.currentId = null
        state.content = state.content.filter((val) => val.id !== item.id)
    },

    // Loading
    loads(state) {
        state.loading = true;
    },
    stoppedLoading(state) {
        state.loading = false;
    },

    // Dates
    setDateRange(state, dates) {
        state.fromDate = dates.fromDate
        state.toDate = dates.toDate
    }
}

// actions
export const actions = {

}
