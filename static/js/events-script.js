document.addEventListener('DOMContentLoaded', () => {
    function base64UrlDecode(str){
        let base64 = str.replace('/-/g', '+').replace('/_/g', '/');
        while (base64.length % 4){
            base64 += '=';
        }
        return atob(base64);
    }
    let buyBtns = document.querySelectorAll('.buy-btn');
    buyBtns.forEach(btn => {
        btn.addEventListener('click', async(e)=>{
            try {
                body = {
                        id: btn.dataset.itemid,
                        type: 'event'
                    };
                    console.log(body);
                const response = await fetch('/store/api/create-order-token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(body)
                });
                const result = await response.json();
                if(!response.ok || !result.success){
                    showError(result.message ?? 'Server error');
                    return;
                }
                //showSuccess(base64UrlDecode(result.token.split('.')[1]));
                if(!result.token){
                    showError('Failed to create order token');
                    return;
                }
                window.location.href = `/store/counter?token=${result.token}`;

            } catch(err){
                console.error(err);
                showError(err);
            }
        })
    })
});