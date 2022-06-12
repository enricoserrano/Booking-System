//Enrico Serano 19071299
//This XHR creates a request using getdata, with the form variables being the parameter
var xhr = createRequest(); 
//This function passes the booking information as a receipt and renders it in a div content in the booking.html
function getData(dataSource, divID, cname, phone, unumber, snumber, stname, sbname, dsbname, date, time)  { 
    if(xhr) { 
        var obj = document.getElementById(divID); 
        var requestbody ="&cname="+encodeURIComponent(cname)+"&phone="+encodeURIComponent(phone)+"&unumber="+encodeURIComponent(unumber)+"&snumber="+encodeURIComponent(snumber)+"&stname="+encodeURIComponent(stname)+"&sbname="+encodeURIComponent(sbname)+"&dsbname="+encodeURIComponent(dsbname)+"&date="+encodeURIComponent(date)+"&time="+encodeURIComponent(time); 
        xhr.open("POST", dataSource, true); 
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
        xhr.onreadystatechange = function() { 
        //alert(xhr.readyState); 
        if (xhr.readyState == 4 && xhr.status == 200) { 
            obj.innerHTML = xhr.responseText; 
        } 
     } 
     xhr.send(requestbody); 
    }
} 