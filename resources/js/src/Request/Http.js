import Requestor from "./Requestor";

export default class Http {
    constructor(baseUrl) {
        this.baseUrl = baseUrl;
    }

    async fetch(endPoint, data, headers, config) {
        config.credentials = 'same-origin';
        config.headers = {
            ...headers,
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': Requestor.getCsrfToken(),
            'Content-Type': 'application/json'
        };

        if (data !== null) {
            if (config.method === 'GET' || config.method === 'DELETE') {
                endPoint += this.buildQueryString(data);
            } else {
                config.body = JSON.stringify(data);
            }
        }

        const url = this.baseUrl + endPoint;
        const response = await window.fetch(url, config);
        return response.json();
    }

    buildQueryString(data) {
        const searchParameters = new URLSearchParams();

        Object.keys(data).forEach(function (parameterName) {
            searchParameters.append(parameterName, data[parameterName]);
        });

        return searchParameters.toString();
    }

    post(endPoint, data = null, headers = {}, config = {}) {
        config.method = 'POST';
        return this.fetch(endPoint, data, headers, config);
    }

    patch(endPoint, data = null, headers = {}, config = {}) {
        config.method = 'PATCH';
        return this.fetch(endPoint, data, headers, config);
    }

    put(endPoint, data = null, headers = {}, config = {}) {
        config.method = 'PUT';
        return this.fetch(endPoint, data, headers, config);
    }

    get(endPoint, data = null, headers = {}, config = {}) {
        config.method = 'GET';
        return this.fetch(endPoint, null, headers, config);
    }

    delete(endPoint, data = null, headers = {}, config = {}) {
        config.method = 'DELETE';
        return this.fetch(endPoint, null, headers, config);
    }
}
