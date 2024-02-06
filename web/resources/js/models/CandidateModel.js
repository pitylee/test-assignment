import BaseModel from './BaseModel';

export default class CandidateModel extends BaseModel {
    constructor() {
        super('/candidate');
        this.reset();
    }


    reset() {
        Object.assign(this.form, {});
    }
}