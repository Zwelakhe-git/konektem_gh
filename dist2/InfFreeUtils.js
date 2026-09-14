;
export async function InfFreeFetch(url, params = {
    method: 'GET',
    headers: {
        'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 YaBrowser/25.8.0.0 Safari/537.36',
        'Connection': 'keep-alive',
    },
    body: undefined
}) {
    try {
        let body = undefined;
        const fetchParams = {
            method: params.method,
            headers: params.headers
        };
        if (params && params.body && params.method === 'POST') {
            fetchParams.headers['Content-Type'] = 'application/x-www-form-urlencoded';
            if (params.body.constructor.name === 'Object') {
                body = new FormData();
                for (const [k, v] of Object.entries(params.body)) {
                    body.append(k, v);
                }
            }
            else if (params.body.constructor.name === 'String') {
                body = params.body;
            }
            else if (params.body.constructor.name === 'FormData') {
                body = params.body;
            }
            fetchParams.body = params.body;
        }
        const resposne = await fetch(url, fetchParams);
        const status = resposne.status;
        const headers = resposne.headers;
        if (status >= 200 && status < 300) {
        }
        if (status >= 300 && status < 400) {
            console.log('redirecting...');
        }
        if (status >= 400 && status < 500) {
            console.error('Client error');
        }
        if (status >= 500) {
            console.error('Server error');
        }
        if (headers.get('Content-Type') === 'application/json') {
            const result = await resposne.json();
            return result;
        }
    }
    catch (e) {
        console.error(e);
    }
}
