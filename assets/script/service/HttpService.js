const getHeaders = (token) => {
    return {
        'X-Requested-With': 'XMLHttpRequest',
        'Authorization': `Bearer ${token}`
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
        let response;
        if (data instanceof FormData) {
            body = data
        } else {
            body = JSON.stringify(data);
        }
        response = await fetch(url, {
            headers: headers ?? getHeaders(token),
            method: 'post',
            body: body
        });

        return await response.json();
    } catch (err) {
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

export {fetchGet, fetchPost, fetchDelete};