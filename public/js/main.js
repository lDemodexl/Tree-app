
/*
    Function to parse json response. 
    if not json returns data back.
*/
function parseJson(code)
{
    try {
        return JSON.parse(code);
    } catch (e) {
        return code;
    }
}

/*Helper funcion to get cookie */
function getCookie(name) {
    let matches = document.cookie.match(new RegExp(
      "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

/*
    Function to send ajax request. 
        method - type of request (post/get)
        data - array/object of data to send
        callback - name of function to run on success response
*/
function simple_ajax(url, method, data, callback = ''){

    var reg_functions = [];
    var type = method;
    var formData = new FormData();

    for (let k in data) {
        formData.append(k, data[k]);
    }

    if (callback != ''){
        reg_functions.push(callback);
    }

    var r = new XMLHttpRequest();
    
    r.addEventListener('readystatechange', function(event){
        if (this.readyState === 4){
            if (this.status === 200){
                
                var data = parseJson(r.responseText);
                if (reg_functions.length > 0) {
                    reg_functions.forEach(function (func) { 
                        return window[func](data);                        
                    });
                }
            }
        }
    })

    r.open(type, url , true);
    r.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    r.send(formData);
    
}

//replace old tree with new one
function updateTree(data){
    var container = document.getElementById('tree-container');
    container.innerHTML = data;
    openDropdown();
}

//add simple element
function addChild(button){
    var container = button.closest('li');
    var id = container.getAttribute('id');
    openDropdownOnAdd(id);
    simple_ajax('trees/add' , 'post', {'createChild':id}, 'updateTree' );
}

//check if root show confirmation else send request to delete element
function deleteElement(button){
    var container = button.closest('li');
    var id = container.getAttribute('id');

    removeFromCoockie(id);

    if(container.classList.contains('root')){
        myModal.show();
        document.getElementById('deleteElementId').value = id;
    }else{
        simple_ajax('trees/delete' , 'post', {'id':id}, 'updateTree' );
    }
}

//send request to delete root element
function confirmDeletion(){
    var id = document.getElementById('deleteElementId').value;
    simple_ajax('trees/delete' , 'post', {'id':id}, 'updateTree' );
    myModal.hide();
}


//create root element on button click
function createRoot(){
    simple_ajax('trees/add' , 'post', {'createRoot':true}, 'updateTree' );
}

//on open/close dropdown resave coockie
function saveDropdown(input){
    var coockie = getCookie('dropdown');
    var name = input.getAttribute('name');
    var opend_array = [];

    if(!coockie){
        document.cookie = 'dropdown='+JSON.stringify(opend_array);
    }else{
        opend_array = parseJson(decodeURIComponent(coockie));
    }

    var index = opend_array.indexOf(name);

    if(input.checked){
        if( index == -1 ){
            opend_array.push(name); 
        }
    }else{
        opend_array.splice(index, 1);
    }

    document.cookie = 'dropdown='+encodeURIComponent(JSON.stringify(opend_array));
}

//remove from coockie if elements are deleted
function removeFromCoockie(id){
    var coockie = getCookie('dropdown');
    if(!coockie){
        document.cookie = 'dropdown='+JSON.stringify(opend_array);
    }else{
        opend_array = parseJson(decodeURIComponent(coockie));
    }

    var container = document.getElementById('marker_'+id).parentNode;
    var childs = container.querySelectorAll('input');

    for( var i=0; i<childs.length; i++ ){
        var index = opend_array.indexOf(childs[i].getAttribute('name'));

        if( index !== -1 ){
            opend_array.splice(index, 1);
        }
        
    }

    document.cookie = 'dropdown='+encodeURIComponent(JSON.stringify(opend_array));
}

//open dropdown when new element added to tree
function openDropdownOnAdd(id){
    var coockie = getCookie('dropdown');
    if(!coockie){
        document.cookie = 'dropdown='+JSON.stringify(opend_array);
    }else{
        opend_array = parseJson(decodeURIComponent(coockie));
    }

    var index = opend_array.indexOf('marker_'+id);
    if( index == -1 ){
        opend_array.push('marker_'+id); 
    }
    document.cookie = 'dropdown='+encodeURIComponent(JSON.stringify(opend_array));
}

//open all dropdown from coockie
function openDropdown(){
    var coockie = getCookie('dropdown');
    if(!coockie){
        document.cookie = 'dropdown='+JSON.stringify(opend_array);
    }else{
        opend_array = parseJson(decodeURIComponent(coockie));
    }

    for( var i=0; i<opend_array.length; i++ ){
        document.getElementById(opend_array[i]).checked = 'checked';
    }
}

//open dropdonw when page loaded
window.addEventListener('load',function(e){
    openDropdown();
});

//init modal
var myModal = new bootstrap.Modal(document.getElementById('myModal'), {
    keyboard: false
})

//init deletion modal events
//bind on show function start timer
var timerId;
var myModalEl = document.getElementById('myModal')
myModalEl.addEventListener('show.bs.modal', function (event) {
    var timer_container = document.getElementById('timer');
    var time = 20;
    timer_container.innerHTML = time;

    timerId = setInterval(function(){
        timer_container.innerHTML = time-1;
        time = time-1;
        if(time==0){
            clearInterval(timerId);
            myModal.hide();
            
        }
    },1000);
})
//bind on hide function clear timer
myModalEl.addEventListener('hidden.bs.modal', function (event) {
    clearInterval(timerId);
})