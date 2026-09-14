import {cachedFetch} from '../api/data-load.js';
const settings = {
    "contact": {},
    "footer": {},
    "social": {},
}

async function loadSettings(){
    try{
        for(const k of Object.keys(settings)){
            //const response = await fetch('/php/dbReader.php?q=siteSettings&group=' + k);
            const data = await cachedFetch('/php/dbReader.php?q=siteSettings&group=' + k, `siteSettings-g-${k}`);
            if(data.success){
                data.settings.forEach(s => {
                    settings[k][s['setting_key']] = s['setting_value'];
                });
            }
        }
    } catch(error){
        console.log(error.message);
    }
}
loadSettings();
export default settings;