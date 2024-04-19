console.log('js file')

let containerResults;
window.addEventListener('load', function() {

    toInnerHtml(`<div id='my-account'>My Account Page</div>
<div id="main-tabs">
    <div id="tab-button-1"
         class="main-tabs-item active"
         onclick="switchTab(this)">
        <div class="text">My Comments</div>
    </div>
    <div id="tab-button-2"
         class="main-tabs-item"
         onclick="switchTab(this)">
        <div class="text">Users</div>
    </div>
    <div id="tab-button-3"
         class="main-tabs-item"
         onclick="switchTab(this)">
        <div class="text">Info</div>
    </div>
</div><div id='container-results'></div>`)

    containerResults = document.getElementById('container-results')

})

function toInnerHtml(data) {
    document.body.innerHTML = data
}

function switchTab(el) {
    toSend({
        clickId: el.id
    })
}
function toSend(clickData) {

    const { clickId } = clickData

    const formData =  new FormData;
    formData.append('tabName', TabsSwitcherHelper.getTabName(clickId));
    formData.append('action', 'switchTabAjax');

    const xhr = new XMLHttpRequest();
    xhr.open('POST', "/wp-admin/admin-ajax.php", true);
    xhr.onreadystatechange = function (res) {
        if (this.readyState === 4 && this.status === 200) {
            console.log(this.response);
            console.log(res);
            containerResults.innerHTML = this.response
        }
        if (this.readyState === 4 && this.status === 404){
            console.log('An error occurred')
        }
    }
    xhr.send(formData);
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