console.log('admin js')

window.addEventListener('load', function() {
	const form = document.getElementById('admin-user-account-form');
	form.addEventListener('submit', event => {
		event.preventDefault();
		saveMyAccountSettingsForm(event.target)
	});
})



function saveMyAccountSettingsForm(elForm) {

	const formData = new FormData(elForm)

	const xhr = new XMLHttpRequest();
	xhr.open('POST', "/wp-json/my-account/v1/admin-save-page-settings/", true);
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

