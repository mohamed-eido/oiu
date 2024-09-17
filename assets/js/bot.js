const chatBox = document.querySelector('.chat-box .wrapper');
const form = document.getElementById('typing-area');
const inputField = document.querySelector('.input-field');
const sendBtn = form.querySelector("button");

inputField.onkeyup = () => {
    if (inputField.value !== "") {
        sendBtn.classList.add("active");
    } else {
        sendBtn.classList.remove("active");
    }
};

form.addEventListener('submit', async (e) => {
    e.preventDefault(); // منع إعادة تحميل الصفحة
    sendBtn.classList.remove("active");

    const userMessage = inputField.value; // الحصول على رسالة المستخدم

    if (userMessage !== "") {
        // عرض رسالة المستخدم
        chatBox.innerHTML += `<div class='box sent'>
                                    <div class='details'>
                                        <p>${userMessage}</p>
                                    </div>
                                </div>`;
        inputField.value = ""; // إعادة تعيين حقل الإدخال

        // AJAX لإرسال البيانات إلى PHP
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "assets/ps/bot.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                const response = JSON.parse(xhr.responseText);
                // عرض رسالة الروبوت
                chatBox.innerHTML += `<div class='box received'>
                                        <div class='details'>
                                            <p>${response.response}</p>
                                        </div>
                                        <div class='img'>
                                            <i class='fa-solid fa-user'></i>
                                        </div>
                                    </div>`;
                chatBox.parentElement.scrollTop = chatBox.parentElement.scrollHeight;
            }
        };
        xhr.send("prompt=" + encodeURIComponent(userMessage));
    }
});