const serialize = (data) => {
    if (typeof data === 'undefined') {
        return null;
    }

    if (typeof data === 'string') {
        return data;
    }
    return JSON.stringify(data);
};
const deserialize = (data) => {
    if (typeof data === 'undefined') {
        return null;
    }

    if (typeof data === 'object') {
        return data;
    }
    return JSON.parse(data);
};

export {
    serialize,
    deserialize,
}