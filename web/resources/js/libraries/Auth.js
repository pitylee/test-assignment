import LoginModel from '~models/LoginModel';
import {store} from '~store';

const loginModel = new LoginModel();

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
}

const me = async () => {
    if (!store.get('me')) {
        await loginModel.me()
            .then(({data}) => {
                store.set('me', data);
            })
            .catch(() => null);
    }
}

export {
    loginModel,
    login,
    me,
}
