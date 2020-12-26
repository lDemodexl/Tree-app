/*   ////////////////      */
/*   Helper functions      */
/*   ///////////////       */
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

/*  /////////////////    */
/*  Action functions     */
/*  ////////////////     */

//Change curent tree
function changeTree(select){
    var treeID = select.value;
    simple_ajax('' , 'post', {'treeID':treeID}, 'updateTree' );
}

//update tree selectbox
function updateTreeSelect(data){
    var container = document.getElementById('treeSelect-container');
    container.innerHTML = data;
}

//replace old tree with new one
function updateTree(data){
    var container = document.getElementById('tree-container');
    container.innerHTML = data;
    openDropdown();
}

//add new tree
function addNewTree(){
    createTreeModal.show(); 
}

//create root element on button click
function createRoot(){
    simple_ajax('trees/add' , 'post', {'createRoot':true}, 'updateTree' );
}

//add simple element
function addChild(button){
    var container = button.closest('li');
    var id = container.getAttribute('id');
    openDropdownOnAdd(id);
    simple_ajax('trees/add' , 'post', {'createChild':id}, 'updateTree' );
}

//edit element name from input
function editElement(button){
    var container = button.parentNode;
    var input = container.querySelector('input');
    var id = input.getAttribute('name');
    var name = input.value;

    simple_ajax('trees/edit' , 'post', {'id':id,'name':name}, 'updateTree' );
}

//create for submit
function createFormSubmit(formID){
    var form = document.getElementById(formID);
    var inputs = form.querySelectorAll('input');

    var formData = [];
    for(var i = 0; i < inputs.length; i++){
        var key = inputs[i].getAttribute('name');
        var value = inputs[i].value;
        formData[key] = value;
    }
    
    createTreeModal.hide();
    form.reset();
    simple_ajax('trees/addTree' , 'post', formData, 'updateTree' );
    simple_ajax('trees/getTreeSelect' , 'post', '', 'updateTreeSelect' );
}

//change element name from form
function editFormSubmit(formID){
    var form = document.getElementById(formID);
    var inputs = form.querySelectorAll('input');

    var formData = [];
    for(var i = 0; i < inputs.length; i++){
        var key = inputs[i].getAttribute('name');
        var value = inputs[i].value;
        formData[key] = value;
    }
    
    renameModal.hide();
    simple_ajax('trees/edit' , 'post', formData, 'updateTree' );
}

//check if root show confirmation else send request to delete element
function deleteElement(button){
    var container = button.closest('li');
    var id = container.getAttribute('id');

    removeFromCoockie(id);

    if(container.classList.contains('root')){
        deleteModal.show();
        document.getElementById('deleteElementId').value = id;
    }else{
        simple_ajax('trees/delete' , 'post', {'id':id}, 'updateTree' );
    }
}

//delete all tree
function deleteTree(){
    var treeID = document.getElementById('selectTree').value;
    deleteModal.show();
    document.getElementById('deleteElementId').value = treeID;
}

//send request to delete root element
function confirmDeletion(){
    var id = document.getElementById('deleteElementId').value;
    var select = document.getElementById('selectTree');
    var event = new Event('change');
    
    select.selectedOptions[0].remove();

    simple_ajax('trees/delete' , 'post', {'id':id}, 'updateTree' );
    deleteModal.hide();
    console.log(select.options);
    if(select.options.length > 0){
        select.selectedIndex=0;
        select.dispatchEvent(event);
    }else{
        simple_ajax('trees/getTreeSelect' , 'post', '', 'updateTreeSelect' );
    }

}

function hideAllNameInputs(){
    var show_data_elements = document.querySelectorAll('.show-element');
    var edit_data_elements = document.querySelectorAll('.edit-element');
    for(var i = 0; i<show_data_elements.length;i++){
        show_data_elements[i].style.display = '';
        edit_data_elements[i].style.display = 'none';
    }
}

//show rename element input
function toogleNameInput(id){
    hideAllNameInputs();
    var renameSettings = document.getElementById('settings_rename_modal');
    if(renameSettings.checked == true){
        document.querySelector('#renameElementId').value = id;
        renameModal.show();
    }else{
        document.getElementById('info_'+id).style.display = 'none';
        document.getElementById('edit_'+id).style.display = '';
    }
    
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

    var container = document.getElementById('marker_'+id);
    if(!!container){
        container = container.parentNode;
        
        var childs = container.querySelectorAll('input[type=checkbox]');

        for( var i=0; i<childs.length; i++ ){
            var index = opend_array.indexOf(childs[i].getAttribute('name'));

            if( index !== -1 ){
                opend_array.splice(index, 1);
            }
            
        }

        document.cookie = 'dropdown='+encodeURIComponent(JSON.stringify(opend_array));
    }
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
        if(!!document.getElementById(opend_array[i])){
            document.getElementById(opend_array[i]).checked = 'checked';
        }
    }
}

//open dropdonw when page loaded
window.addEventListener('load',function(e){
    openDropdown();
});


/*  /////////////////////// */
/*  Modals inits and events */
/*  /////////////////////// */

//init delete modal
var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'), {
    keyboard: false
})

//init deletion modal events
//bind on show function start timer
var timerId;
var deleteModalEl = document.getElementById('deleteModal')

deleteModalEl.addEventListener('show.bs.modal', function (event) {
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
deleteModalEl.addEventListener('hidden.bs.modal', function (event) {
    clearInterval(timerId);
})

//init rename modal
var renameModal = new bootstrap.Modal(document.getElementById('renameModal'), {
    keyboard: false
})

//init rename modal
var createTreeModal = new bootstrap.Modal(document.getElementById('createTreeModal'), {
    keyboard: false
})