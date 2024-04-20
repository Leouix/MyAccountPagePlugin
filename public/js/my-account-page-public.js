console.log('js file')

let containerResults;
let userForm;
let userFormClassObject;

window.addEventListener('load', function() {
    containerResults = document.getElementById('container-results')
})

function triggerUserForm() {
    userForm = document.getElementById("user-data-form")

    const form = new FormData(userForm)
    userFormClassObject = new UserDataForm(form)
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
    formData.append('action', 'switchTabAjax');

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

class UserDataForm {

    user = {}
    changedFields = []

    constructor(form) {
        const {
            ID,
            user_login,
            user_nicename,
            user_email,
            user_registered,
            display_name,
            user_url,
            nickname,
            first_name,
            last_name,
            description,
        } = form

        this.user.ID = ID
        this.user.user_login = user_login
        this.user.user_nicename = user_nicename
        this.user.user_email = user_email
        this.user.user_registered = user_registered
        this.user.display_name = display_name
        this.user.user_url = user_url
        this.user.nickname = nickname
        this.user.first_name = first_name
        this.user.last_name = last_name
        this.user.description = description
    }

    checkIsUserChanged() {
        return this.changedFields.length > 0
    }

    getNameField(el) {
        return el.getAttribute('name')
    }

    editingUserData(elInput) {

        const fieldName = this.getNameField(elInput)

        if (this.isFieldChanged(elInput)) {
            if (!this.changedFields.includes(fieldName)) {
                this.changedFields.push(fieldName)
            }
        } else {
            this.removeFromChangedFields(fieldName)
        }

        if (this.checkIsUserChanged()) {
            toggleBtn(true)
        } else {
            toggleBtn(false)
        }
    }

    isFieldChanged(el) {
        const dataOrig = el.getAttribute("data-orig")
        const value = el.value
        return dataOrig !== value
    }

    removeFromChangedFields(fieldName) {
        const index = this.changedFields.indexOf(fieldName);
        if (index > -1) {
            this.changedFields.splice(index, 1);
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
}