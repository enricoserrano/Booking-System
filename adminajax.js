//Enrico Serano 19071299
//This XHR creates a request using getdata, with the form variables being the parameter
var xhr = createRequest(); 
//This function is called when a user wishes to query a result in the admin page
function getData(dataSource, divID, bsearch)  { 
    if(xhr) { 
        document.getElementById("message").innerHTML = "";
        localStorage.setItem("bsearchid", bsearch);
        var obj = document.getElementById(divID); 
        var requestbody ="&bsearch="+encodeURIComponent(bsearch); 
        xhr.open("POST", dataSource, true); 
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
        xhr.onreadystatechange = function() { 
        if (xhr.readyState == 4 && xhr.status == 200) { 
            obj.innerHTML = xhr.responseText; 
        } 
     } 
     xhr.send(requestbody); 
     
    }
} 

//This function reloads the table after the user has clicked assign in the admin and prompts the user for the confirmation message
function reloadData(dataSource, divID, bsearch)  { 
    var xhr = createRequest(); 
    document.getElementById("content").innerHTML = "";
    if(xhr) { 
        document.getElementById("message").innerHTML = "Congratulations! Booking request " + bsearch +" has been assigned!";
        var obj = document.getElementById(divID); 
        var requestbody ="&bsearch="+encodeURIComponent(bsearch); 
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
//This function is called from the assign button from 'admin.php' to rerender the table and update the information. It will also display a confirmation message
function showMessage()  { 
    let queryid = localStorage.getItem("bsearchid"); 
    reloadData("admin.php","content", queryid);
} 
