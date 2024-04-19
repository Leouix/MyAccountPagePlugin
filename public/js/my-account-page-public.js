console.log('js file')

let containerResults;

window.addEventListener('load', function() {
    containerResults = document.getElementById('container-results')
})

function switchTab(el) {
    toSend({
        clickId: el.id
    })
}
function toSend(clickData) {

    const { clickId } = clickData

    const formData =  new FormData;
    formData.append('tabName', TabsSwitcherHelper.getTabName(clickId));
    formData.append('actionWanted', 'toGet');
    formData.append('action', 'switchTabAjax');

    const xhr = new XMLHttpRequest();
    xhr.open('POST', "/wp-admin/admin-ajax.php", true);
    xhr.onreadystatechange = function (res) {
        if (this.readyState === 4 && this.status === 200) {
            console.log(this)
            containerResults.innerHTML = this.response
        }
        if (this.readyState === 4 && this.status === 404){
            console.log('An error occurred')
        }
    }
    xhr.send(formData);
}

let isFormChanged = false;

function editingUserData(el) {

    const dataOrig = el.getAttribute("data-orig")
    const value = el.value

    if (dataOrig !== value) {
        toggleBtn(true)
    } else {
        toggleBtn(false)
    }
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