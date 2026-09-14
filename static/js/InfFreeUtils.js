export async function InfFreeFetch(url, params = {
    method: 'GET',
    headers: {},
    body: undefined
}) {
    try {
        let body = undefined;
        let fetchParams = {
            method: params.method,
            headers: {
                'User-Agent': navigator.userAgent,
                'Connection': 'keep-alive',
            }
        };
        const bodyType = params.body.constructor.name;
        if (params && params.body && (params.method === 'POST' || params.method === 'PUT' || params.method === 'PATCH' || params.method === 'DELETE')) {
            if (bodyType === 'Object') {
                body = new FormData();
                for (const [k, v] of Object.entries(params.body)) {
                    body.append(k, v);
                }
            }
            else if (bodyType === 'String') {
                fetchParams.headers['Content-Type'] = 'application/json';
                body = params.body;
            }
            else if (bodyType === 'FormData') {
                delete params.headers['Content-Type'];
                body = params.body;
            } else if(bodyType === 'URLSearchParams'){
                params.headers['Content-Type'] = 'application/x-www-form-urlencoded';
                body = params.body.toString();
            }
            fetchParams.body = body;
        }
        if(params.headers && Object.keys(params.headers).length > 0){
            fetchParams.headers = {...fetchParams.headers, ...params.headers};
        }
        fetchParams['credentials'] = 'same-origin';
        const response = await fetch(url, fetchParams);
        const status = response.status;
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
        return response;
    }
    catch (e) {
        console.error(e);
    }
}
