console.log('admin js')
let formUserButton
let successNotice
let lockUrlIcon
let urlInput
let dashiconsUnlock
let dashiconsLock
window.addEventListener('load', function() {
	const form = document.getElementById('admin-user-account-form');
	form.addEventListener('submit', event => {
		event.preventDefault();
		saveMyAccountSettingsForm(event.target)
	});

	formUserButton = document.getElementById('save-create-button')
	successNotice = document.getElementById('success-notice')
	lockUrlIcon = document.getElementById('lock-url')
	urlInput = document.getElementById('adu-form-input')
	dashiconsUnlock = document.getElementById('dashicons-unlock')
	dashiconsLock = document.getElementById('dashicons-lock')

	lockUrlIcon.addEventListener('click', function () {
		urlInput.disabled = !urlInput.disabled;

		if (urlInput.disabled) {
			dashiconsLock.classList.remove('hidden')
			dashiconsLock.classList.add('visible')

			dashiconsUnlock.classList.remove('visible')
			dashiconsUnlock.classList.add('hidden')
		} else {
			dashiconsLock.classList.remove('visible')
			dashiconsLock.classList.add('hidden')

			dashiconsUnlock.classList.remove('hidden')
			dashiconsUnlock.classList.add('visible')
		}

	})
})



function saveMyAccountSettingsForm(elForm) {

	const formData = new FormData(elForm)

	const xhr = new XMLHttpRequest();
	xhr.open('POST', "/wp-json/my-account/v1/admin-save-page-settings/", true);
	xhr.onreadystatechange = function (res) {
		if (this.readyState === 4 && this.status === 200) {
			// console.log(this.response)
			successAjaxButtonEvent("success")
			lockLinkField()

			// let json = JSON.parse(this.response)
			// containerResults.innerHTML = json.html
		}
		if (this.readyState === 4 && this.status === 404){
			console.log('An error occurred')
		}
	}
	xhr.send(formData);
}

function lockLinkField() {
	urlInput.disabled = true
	dashiconsLock.classList.remove('hidden')
	dashiconsLock.classList.add('visible')
	dashiconsUnlock.classList.remove('visible')
	dashiconsUnlock.classList.add('hidden')
}

function successAjaxButtonEvent(statusClass) {
	formUserButton.classList.add(statusClass)
	successNotice.style.display = 'block'
	if (statusClass === 'success') {
		setTimeout(()=> {
			formUserButton.classList.remove(statusClass)
			successNotice.style.display = 'none'
		}, 1000)
	}
}