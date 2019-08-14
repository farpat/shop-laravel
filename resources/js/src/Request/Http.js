export default class Http {
    constructor(baseUrl) {
        this.baseUrl = baseUrl;
    }

    static getCsrfToken() {
        if (Http.csrfToken === undefined) {
            Http.csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        }

        return Http.csrfToken;
    }

    async fetch(endPoint, data, headers, config) {
        config.credentials = 'same-origin';
        config.headers = {
            ...headers,
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': Http.getCsrfToken(),
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

        if (response.ok) {
            return response.json();
        }
        else {
            throw response.json();
        }
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
