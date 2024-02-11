import Api from '~libraries/Api';
import {serialize, deserialize} from '~libraries/Json';

export default class BaseModel {
    constructor(baseUrl, autoHandle = true) {
        this.api = Api();
        this.form = {};
        this.originalForm = {};
        this.baseUrl = baseUrl;
        this.useUpdateId = true;
        this.loading = false;
        this.setupAxiosInterceptors();
        this.validated = [];
        this.autoHandle = autoHandle;
    }

    setupAxiosInterceptors() {
        this.loading = false;

        this.api.interceptors.response.use(
            (response) => {
                this.loading = true;
                return Promise.resolve(response);
            },
            (handler) => {
                this.loading = false;
                return Promise.reject(handler);
            },
        );
    }

    async all(params) {
        return this.api
            .get(this.baseUrl, {params})
            .then((data) => {
                const formattedData = deserialize(data.data);
                return Promise.resolve(formattedData);
            });
    }

    // When not providing an ID to a find, we will get the endpoint without the ID in the endpoint.
    async find(id, params, queryParams) {
        const idString = (undefined === id) ? '' : `/${id}`;
        const queryParamsString = (undefined === queryParams) ? '' : queryParams;
        return this.api
            .get(`${this.baseUrl}${idString}${queryParamsString}`, {params})
            .then((data) => {
                const formattedData = deserialize(data.data);
                Object.assign(this.form, formattedData.data);
                Object.assign(this.originalForm, formattedData.data);
                return Promise.resolve(formattedData);
            });
    }

    async custom(url, params, method = 'post') {
        if (method === 'get') {
            return this.api
                .get(url, params)
                .then((data) => {
                    const formattedData = deserialize(data.data);
                    return Promise.resolve(formattedData);
                });
        }

        return this.api
            .post(url, params)
            .then((data) => {
                const formattedData = deserialize(data.data);
                return Promise.resolve(formattedData);
            });
    }

    getForm() {
        return this.form;
    }

    async create(config = null) {
        const saveData = serialize(this.getForm());
        this.validated = [];

        return this.api.post(this.baseUrl, saveData, config);
    }

    async customCreate() {
        return this.api.post(this.baseUrl, this.getForm());
    }

    async update() {
        const saveData = serialize(this.getForm());
        const useIdString = (undefined === this.form.id || this.useUpdateId === false) ? '' : `/${this.form.id}`;

        this.validated = [];
        return this.api.patch(`${this.baseUrl}${useIdString}`, saveData)
            .then((data) => {
                const formattedData = deserialize(data.data);
                Object.assign(this.form, formattedData.data);
                Object.assign(this.originalForm, formattedData.data);
                return Promise.resolve(formattedData);
            });
    }

    async customUpdate(url, updateData) {
        return this.api.patch(`${url}`, updateData);
    }

    async delete(id, addData = false) {
        let deleteData;
        if (addData) {
            deleteData = serialize(this.getForm());
        }

        return this.api.delete(`${this.baseUrl}/${id}`, deleteData)
            .then((data) => {
                const formattedData = deserialize(data.data);
                Object.assign(this.form, formattedData.data);
                Object.assign(this.originalForm, formattedData.data);
                return Promise.resolve(formattedData);
            });
    }

    async customDelete(url = this.baseUrl, data = undefined) {
        if (data !== undefined) {
            return this.api.delete(url, {data});
        }
        return this.api.delete(url);
    }
}
