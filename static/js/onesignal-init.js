
export async function initOS(){
    let ts = (new Date()).getTime();
    console.log("initializing onesignal");
    const script = document.createElement("script");
    script.defer;
    script.src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js";
    document.head.appendChild(script);
    script.onload = ()=>{
        let te = (new Date()).getTime();
        console.log("OS script loaded. time lapsed " + (te - ts) + 's');
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(async function(OneSignal) {
            await OneSignal.init({
                appId: "321ec692-e005-4b5e-94aa-92178478302f",
            });
        });
    }
}