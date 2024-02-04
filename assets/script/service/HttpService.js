import env from '../env.js';

const getHeaders = (token, isFormData = false) => {
    let ob = {
        'X-Requested-With': 'XMLHttpRequest',
        'Authorization': `Bearer ${token}`,
    };
    if(!isFormData)ob['Content-type'] = 'application/ld+json';
    return ob;
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
            headers: headers ?? getHeaders(token, data instanceof FormData),
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
        let patchHeaders = headers ?? getHeaders(token);
        patchHeaders['Content-type'] = "application/merge-patch+json";
        if (data instanceof FormData) {
            throw new Error('Not FormDate in Patch');
        } else {
            body = JSON.stringify(data);
        }
        response = await fetch(url, {
            headers: patchHeaders,
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

/**
 * @returns {Promise<string|null>}
 */
async function getToken() {
    let response = await fetchGet(`${env['basUrl']}/admin/get-token`);
    if (response['status'] === 1)return response['data'];

    return null;
}

export {fetchGet, fetchPost, fetchPatch, fetchDelete, getToken};