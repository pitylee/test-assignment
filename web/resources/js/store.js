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
}

const store = new State();

export {
    store,
};