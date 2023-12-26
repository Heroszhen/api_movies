const getHeaders = (token) => {
    return {
        'X-Requested-With': 'XMLHttpRequest',
        'Authorization': `Bearer ${token}`,
        'Content-type': 'application/ld+json'
    }
};

/**
 * @param {string} url 
 * @param {string} [token=''] token
 * @param {Object} [headers=null] headers
 * @param {Function} [func=null] func
 * 
 * @returns {Promise<Object>}
 */
async function fetchGet(url, token = "", headers = null, func = null)  {
    try {
        let response = await fetch(url, {
            headers: headers ?? getHeaders(token),
            method: 'GET'
        });

        return await response.json();
    } catch (err) {
        func;
        throw new Error(err);
    }
}

/**
 * @param {string} url 
 * @param {FormData|Object} data 
 * @param {string} [token=''] token
 * @param {Object} [headers=null] headers
 * @param {Function} [func=null] func
 * 
 * @returns {Promise<Object>}
 */
 async function fetchPost(url, data, token = '', headers = null, func = null) {
    try {
        let response, body;
        if (data instanceof FormData) {
            body = data
        } else {
            body = JSON.stringify(data);
        }
        response = await fetch(url, {
            headers: headers ?? getHeaders(token),
            method: 'POST',
            body: body
        });

        return await response.json();
    } catch (err) {
        func;
        throw new Error(err);
    }
}

/**
 * @param {string} url 
 * @param {FormData|Object} data 
 * @param {string} [token=''] token
 * @param {Object} [headers=null] headers
 * @param {Function} [func=null] func
 * 
 * @returns {Promise<Object>}
 */
 async function fetchPatch(url, data, token = '', headers = null, func = null) {
    try {
        let response, body;
        let patchHeaders = getHeaders(token);
        patchHeaders['Content-type'] = "application/merge-patch+json";
        if (data instanceof FormData) {
            body = data
        } else {
            body = JSON.stringify(data);
        }
        response = await fetch(url, {
            headers: headers ?? patchHeaders,
            method: 'PATCH',
            body: body
        });

        return await response.json();
    } catch (err) {
        func;
        throw new Error(err);
    }
}

/**
 * @param {string} url 
 * @param {string} [token=''] token
 * @param {Object} [headers=null] headers
 * @param {Function} [func=null] func
 * 
 * @returns {Promise<Object>}
 */
 async function fetchDelete(url, token = '', headers = null, func = null) {
    try {
        let response = await fetch(url, {
            headers: headers ?? getHeaders(token),
            method: 'DELETE'
        });

        return await response.json();
    } catch (err) {
        func;
        throw new Error(err);
    }
}

export {fetchGet, fetchPost, fetchPatch, fetchDelete};