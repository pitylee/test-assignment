import BaseModel from './BaseModel';

export default class ContactModel extends BaseModel {
    constructor() {
        super('/contact');
        this.reset();
    }

    reset() {
        Object.assign(this.form, {
            subject: '',
            message: '',
            id: null,
        });
    }

    sendMessage() {
        return this.create('/me');
    }
}