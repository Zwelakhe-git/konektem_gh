import {cachedFetch} from '../api/data-load.js';
const interviewsData = {
    pageTitle: "Entèvyou",
    pageDescription: "Dekouvri tout entèvyou ak moun enpòtan yo",
    interviews: []
};

cachedFetch('/php/dbReader.php?r=interviews', 'interviewsData')
.then(data => {
    interviewsData.interviews = data;
});
export {interviewsData}