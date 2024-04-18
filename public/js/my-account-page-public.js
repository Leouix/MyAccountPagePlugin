console.log('js file')

function clickHandle(el) {
    toSend({
        clickId: el.id
    })
}

function toSend(clickData) {

    const { clickId } = clickData

    const formData =  new FormData;
    formData.append('tabName', TabsSwitcher.getTabName(clickId));
    formData.append('action', 'addItemAJAX');

    const xhr = new XMLHttpRequest();
    xhr.open('POST', "/wp-admin/admin-ajax.php", true);
    xhr.onreadystatechange = function (res) {
        if (this.readyState === 4 && this.status === 200) {
            console.log(this.response);
        }
        if (this.readyState === 4 && this.status === 404){
            console.log('An error occurred')
        }
    }
    xhr.send(formData);
}

class TabsSwitcher {
    static tabs = {
        "tab-button-1": "my-comments",
        "tab-button-2": "users",
        "tab-button-3": "info",
    }
    static getTabName(buttonId) {
        return this.tabs[buttonId]
    }
}