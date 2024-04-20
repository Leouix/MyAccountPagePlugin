console.log('js file')

let containerResults;
let userFormClassObject;

window.addEventListener('load', function() {
    containerResults = document.getElementById('container-results')
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
    xhr.open('POST', "/wp-json/my-account/v1/user-tab/", true);
    xhr.onreadystatechange = function (res) {
        if (this.readyState === 4 && this.status === 200) {
            console.log(this.response)
            // let json = JSON.parse(this.response)
            // containerResults.innerHTML = json.html
        }
        if (this.readyState === 4 && this.status === 404){
            console.log('An error occurred')
        }
    }
    xhr.send(formData);

}

function switchTab(el) {
    getPage({
        clickId: el.id
    })
}
function getPage(clickData) {

    const { clickId } = clickData

    const formData =  new FormData;
    formData.append('tabName', TabsSwitcherHelper.getTabName(clickId));
    formData.append('actionWanted', 'toGet');
  //  formData.append('action', 'switchTabAjax');

    const xhr = new XMLHttpRequest();
    xhr.open('POST', "/wp-json/my-account/v1/switchTabAjax/", true);
    xhr.onreadystatechange = function (res) {
        if (this.readyState === 4 && this.status === 200) {
           // console.log(this.response)
            let json = JSON.parse(this.response)
            containerResults.innerHTML = json.html

            triggerUserForm()
        }
        if (this.readyState === 4 && this.status === 404){
            console.log('An error occurred')
        }
    }
    xhr.send(formData);
}

function editingUserData(el) {
    userFormClassObject.editingUserData(el)
}

function toggleBtn(isFormChanged) {
    let formUserButton = document.getElementById('form-user-button')
    if (formUserButton !== undefined && formUserButton !== null) {
        isFormChanged ? formUserButton.style.display = 'block' : formUserButton.style.display = 'none'
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
}