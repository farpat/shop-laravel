import io from 'socket.io-client';
import Http from './Http';

export default class Requestor {
    constructor() {
        this.csrfToken = '';
    }

    static setIo() {
        window.io = window.io || io;
    }

    /**
     * @param baseUrl
     * @returns {Http}
     */
    static newRequest(baseUrl = '') {
        return new Http(baseUrl);
    }

    static getCsrfToken() {
        if (this.csrfToken === '') {
            this.csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        }

        return this.csrfToken;
    }
}
