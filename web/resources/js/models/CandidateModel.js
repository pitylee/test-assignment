import BaseModel from './BaseModel';

export default class CandidateModel extends BaseModel {
    constructor() {
        super('/candidates');
        this.reset();
    }

    reset() {
        Object.assign(this.form, {});
    }
}