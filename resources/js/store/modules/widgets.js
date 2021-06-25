import Vue from 'vue'

export const namespaced = true

// state
export const state = {
    content: [],
    loading: false
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
        items.map((item) => {
            return item
        })
        state.content = items
        if (state.currentId === null && items.length) state.currentId = items[0].id
    },
    addOrUpdate(state, item) {
        state.content = state.content.filter((val) => val.id !== item.id)
        // Set initial position
        const widget = {...item}
        if (!widget.position) {
            widget.position = {
                x: (state.content.length * 3) % 6,
                y: state.content.length * 6, // puts it at the bottom
                w: 3,
                h: 4,
                i: widget.id
            }
        }
        state.content.push(widget)
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
    }
}

// actions
export const actions = {}
