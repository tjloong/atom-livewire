export default class 
{
    url
    headers

    constructor (url) {
        this.url = url
        this.headers = {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }

    get (payload) {
        return fetch(this.url, {
            method: 'GET',
            body: JSON.stringify(payload),
            headers: this.headers,
        }).then(res => (res.json()))
    }

    post (payload) {
        return fetch(this.url, {
            method: 'POST',
            body: payload instanceof FormData ? payload : JSON.stringify(payload),
            headers: this.headers,
        }).then(res => (res.json()))
    }
}