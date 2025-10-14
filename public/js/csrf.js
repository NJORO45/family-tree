addEventListener("DOMContentLoaded",()=>{
    const csrtfToken = document.querySelectorAll(".csrtfToken");
    async function csrtfTokenFunction() {
        const response = await fetch('csrfTokenGenerator.php',{
            method:"GET",
            headers:{"Accept":"application/json"}     
        });
        const text = await response.text();
        //console.log(text);
        try{
            const results = JSON.parse(text);
            if(results){
                csrtfToken.forEach((e)=>{
                    e.value=results.csrfToken;
                });
            }
        }catch(jsonErr){
            console.log(" json error:" + jsonErr);
        }
    }
    setTimeout(()=>{
        csrtfTokenFunction();
    },500);
});