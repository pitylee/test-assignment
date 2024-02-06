import BaseModel from './BaseModel';

export default class LoginModel extends BaseModel {
    constructor() {
        super('/login');
        this.reset();
    }

    reset() {
        Object.assign(this.form, {});
    }

    me() {
        return this.custom(`/me`, {}, 'get');
    }
}