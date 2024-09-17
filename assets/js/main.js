let content = document.querySelectorAll("#content");
let Sections = document.querySelectorAll(".content section");
let Bar = document.querySelector(".bar");
let Buttons = document.querySelectorAll("nav .btn");
let loader = document.querySelector(".spinner");
let title = document.querySelector("header .title");

window.onload = function() {
    const activeBtnClass = document.cookie.split('; ').find(row => row.startsWith('activeBtn='))
        ?.split('=')[1];
    if (activeBtnClass) {
        const activeBtn = document.querySelector(`.nav .btn.${activeBtnClass}`);
        if (activeBtn) {
            activeBtn.classList.add("active");
            indicator(activeBtn);

            title.textContent = activeBtn.getAttribute("custom");
        
            Sections.forEach((Section) => {
                if (Section.classList.contains(activeBtnClass)) {
                    Section.classList.add("active");
                } else {
                    Section.classList.remove("active");
                }
            });
        }
    }
};

function indicator(e) {
    Bar.style.right = window.innerWidth - e.getBoundingClientRect().right + "px";
    Bar.style.width = e.offsetWidth + "px";
}

Buttons.forEach((Button) => {
    Button.addEventListener("click", (e) => {
        loader.classList.remove("loaded");
        indicator(e.target);

        Buttons.forEach((Button) => {
            Button.classList.remove("active");
        });
        e.target.classList.add("active");
        document.cookie = `activeBtn=${e.target.classList[1]}; path=/; max-age=${7 * 24 * 60 * 60}`;

        title.textContent = e.target.getAttribute("custom");
        
        let temp = e.target.className.split(" ");
        
        Sections.forEach((Section) => {
            if (Section.classList.contains(temp[1])) {
                Section.classList.add("active");
            } else {
                Section.classList.remove("active");
            }
        });

        setTimeout(() => {
            loader.classList.add("loaded");
        }, 3000);
        
    });
});

////// logout /////////////////////////////

function toggle() {
    let blur = document.querySelector("#blur");
    blur.classList.toggle("active");

    let popup = document.querySelector(".popup");
    popup.classList.toggle("active");
}

function logout() {
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = () => {
        if (xhr.readyState === 4 && xhr.status === 200) {
            window.location.href = "login.php";
        }
    }
    xhr.open("POST", "assets/ps/logout.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send();
}