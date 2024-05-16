console.log('js file')

let containerResults;
let userFormClassObject;
let tabButton1;
let tabButton2;
let tabButton3;


window.addEventListener('load', function() {
    containerResults = document.getElementById('container-results')
    tabButton1 = document.getElementById('tab-button-1')
    tabButton2 = document.getElementById('tab-button-2')
    tabButton3 = document.getElementById('tab-button-3')

    checkTabGetParamsLoading()
})

function triggerUserForm() {
    userFormClassObject = new UserDataForm()

    const form = document.getElementById('user-data-form');
    form.addEventListener('submit', event => {
        event.preventDefault();
        userFormSubmit(event.target)
    });

}

function userFormSubmit(elForm) {

    const formData = new FormData(elForm)

    const xhr = new XMLHttpRequest();
    xhr.open('POST', "/wp-json/my-account/v1/info-tab/", true);
    xhr.onreadystatechange = function (res) {
        if (this.readyState === 4 && this.status === 200) {
            console.log(this.response)
            // let json = JSON.parse(this.response)
            // containerResults.innerHTML = json.html

            successAjaxButtonEvent('success')
        }
        if (this.readyState === 4 && this.status === 404){
            console.log('An error occurred')
            successAjaxButtonEvent('warning')
        }
    }
    xhr.send(formData);

}

function switchTab(el) {
    getPage({
        clickId: el.id
    })
    TabsSwitcherHelper.switch(el.id)
}

function getPage(clickData) {

    const { clickId } = clickData

    const formData =  new FormData;
    formData.append('tabName', TabsSwitcherHelper.getTabName(clickId));
  //  formData.append('actionWanted', 'toGet');
  //  formData.append('action', 'switchTabAjax');

    const xhr = new XMLHttpRequest();
    xhr.open('POST', "/wp-json/my-account/v1/switchTabAjax/", true);
    xhr.onreadystatechange = function (res) {
        if (this.readyState === 4 && this.status === 200) {
           // console.log(this.response)
            let json = JSON.parse(this.response)
            containerResults.innerHTML = json.html
            replaceUrlParam(TabsSwitcherHelper.getTabName(clickId))

            if (TabsSwitcherHelper.getTabName(clickId) === 'info') {
                triggerUserForm()
            }
        }
        if (this.readyState === 4 && this.status === 404){
            console.log('An error occurred')
        }
    }
    xhr.send(formData);
}

function successAjaxButtonEvent(statusClass) {
    let formUserButton = document.getElementById('form-user-button')
    formUserButton.classList.add(statusClass)
    if (statusClass === 'success') {
        setTimeout(()=> toggleBtn(false), 1500)
    }
}

function editingUserData(el) {
    userFormClassObject.editingUserData(el)
}

function toggleBtn(isFormChanged) {
    let formUserButton = document.getElementById('form-user-button')
    if (formUserButton !== undefined && formUserButton !== null) {
        isFormChanged ? formUserButton.style.display = 'block' : formUserButton.style.display = 'none'
        if (isFormChanged) {
            formUserButton.classList.remove('success')
            formUserButton.classList.remove('warning')
        }
    }
}

class TabsSwitcherHelper {
    static tabs = {
        "tab-button-1": "my-comments",
        "tab-button-2": "users",
        "tab-button-3": "info",
    }
    static getTabName(buttonId) {
        return this.tabs[buttonId]
    }

    static switch(activeTabId) {

        tabButton1.classList.remove('active')
        tabButton2.classList.remove('active')
        tabButton3.classList.remove('active')

        switch (activeTabId) {
            case 'tab-button-1':
                tabButton1.classList.add('active')
                break;
            case 'tab-button-2':
                tabButton2.classList.add('active')
                break;
            case 'tab-button-3':
                tabButton3.classList.add('active')
                break;
        }
    }
}
function getNavUrl() {
    // Get URL
    return window.location.search.replace("?", "");
}

function getParameters(url) {
    // Params obj
    var params = {};
    // To lowercase
    url = url.toLowerCase();
    // To array
    url = url.split('&');

    // Iterate over URL parameters array
    var length = url.length;
    for(var i=0; i<length; i++) {
        // Create prop
        var prop = url[i].slice(0, url[i].search('='));
        // Create Val
        var value = url[i].slice(url[i].search('=')).replace('=', '');
        // Params New Attr
        params[prop] = value;
    }
    return params;
}

function checkTabGetParamsLoading() {

    const params = getParameters(getNavUrl())

    if (params?.tab === 'my-comments') {
        getPage({
            clickId: "tab-button-1"
        })
        TabsSwitcherHelper.switch("tab-button-1")
    } else if (params?.tab === 'users') {
        getPage({
            clickId: "tab-button-2"
        })
        TabsSwitcherHelper.switch("tab-button-2")
    } else { // if (params?.tab === 'info')
        getPage({
            clickId: "tab-button-3"
        })
        TabsSwitcherHelper.switch("tab-button-3")
    }

}

function replaceUrlParam(paramValue)
{
    const queryParams = new URLSearchParams(window.location.search);
    queryParams.set("tab", paramValue);
    history.replaceState(null, null, "?"+queryParams.toString());
}
