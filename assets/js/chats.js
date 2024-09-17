const searchBar = document.querySelector(".search input");
const usersList = document.querySelector(".users-list");

searchBar.onblur = () => {
	if (searchBar.classList.contains("active")) {
		searchBar.value = "";
		searchBar.classList.remove("active");
	}
	usersList.innerHTML = lastData;
}

searchBar.onkeyup = () => {
	let searchTerm = searchBar.value;

	if (searchTerm != "") {
	  searchBar.classList.add("active");
	} else {
	  searchBar.classList.remove("active");
	}

	if (searchTerm.length > 0) {
		let xhr = new XMLHttpRequest();
		xhr.onreadystatechange = () => {
			if (xhr.readyState === 4 && xhr.status === 200) {
				let data = xhr.response;
				usersList.innerHTML = data;
			}
		}
		xhr.open("POST", "assets/ps/get_chats.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		const params = `searchTerm=${searchTerm}`;
		xhr.send(params);
	}
}

let lastData = "";

let getMessages = () => {
	let xhr = new XMLHttpRequest();
	xhr.onreadystatechange = () => {
		if (xhr.readyState === 4 && xhr.status === 200) {
			let data = xhr.response;

			if (data !== lastData && usersList.innerHTML !== data && !searchBar.classList.contains("active")) {
				usersList.innerHTML = data;
				lastData = data;
			}
		}
	}
	xhr.open("GET", "assets/ps/get_chats.php", true);
	xhr.send();
}

setInterval(() => {
	getMessages();
}, 500);