import Axios from 'axios';
import {store} from '~store';

export default () => {
    const headers = {
        Accept: 'application/json',
        'Content-Type': 'application/json',
    };
    const token = store.get('token');
    if (token) {
        headers.Authorization = `Bearer ${token}`;
    }

    const Api = Axios.create({
        baseURL: `${process.env.API_ROOT}/api` || 'http://localhost/api',
        timeout: process.env.API_DEFAULT_TIMEOUT || (60 * 1000),
        withCredentials: true,
        xsrfCookieName: 'Csrf-Token',
        xsrfHeaderName: 'X-Csrf-Token',
        headers,
    });

    Api.interceptors.response.use(
        (config) => {
            const apiResponsePromise = Promise.resolve(config);
            apiResponsePromise.then((apiResponse) => {
                if (apiResponse.status === 401) {
                    console.log('You are not authorized');
                }
            });
            return apiResponsePromise;
        },
        (error) => {
            // if (error.response && error.response.data) {
            //     return Promise.reject(error.response.data);
            // }
            // return Promise.reject(error.message);
            return Promise.reject(error);
        },
    );

    return Api;
};
