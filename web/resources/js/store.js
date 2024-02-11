import Vue from "vue";

class State {
    constructor() {
        this._state = {};
    }

    // Getter for the entire state object
    get state() {
        console.log('called');
        return this._state;
    }

    // Setter for the entire state object
    set state(newState) {
        this._state = newState;
    }

    // Get method to retrieve a property value
    get(key) {
        return this._state[key];
    }

    // Set method to set a property value
    set(key, value) {
        this._state[key] = value;
    }

    get errors() {
        const errors = store._state.errors;

        if (errors?.length > 0) {
            store._state.errors = {};
        }

        return errors;
    }

    // Set method to set a property value
    setErrors(errors = null) {
        if (typeof store._state.errors === 'undefined') {
            store._state.errors = {};
        }
        const {stack, message, error, detail, fields} = errors;
        const key = btoa(`${stack ?? error ?? detail}`);

        store._state.errors[key] = {
            ...{stack, message},
            ...{error, detail},
            ...{fields},
        };
        Vue.set(store._state.errors, key, store._state.errors[key])
    }
}

const store = new State();

export {
    store,
};