import { refreshCsrfTokens } from './utilities/alerthandler.js';
addEventListener("DOMContentLoaded",()=>{
  
    setTimeout(()=>{
        refreshCsrfTokens();
    },500);
});