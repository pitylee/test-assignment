import LoginModel from '~models/LoginModel';
import {store} from '~store';

const loginModel = new LoginModel();

/*
* Will login to retrieve a token, it is a simple auth, mainly to have a user on backend
* */
const login = async () => {
    if (!store.get('token')) {
        // usually redirect here?
        await loginModel.create()
            .then(({data}) => {
                if (data?.me) {
                    store.set('me', data.me);
                }
                store.set('token', data.token);
            })
            .catch(() => null);
    }
};

/*
* Will get data from /me endpoint, containing user and coins data
* */
const me = async (refresh = false) => {
    if (refresh || !store.get('me')) {
        await loginModel.me()
            .then(({data}) => {
                store.set('me', data);
            })
            .catch(() => null);
    }
};

export {
    loginModel,
    login,
    me,
};
