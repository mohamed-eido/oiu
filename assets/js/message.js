const form = document.querySelector(".typing-area");
const inputField = form.querySelector(".input-field");
const sendBtn = form.querySelector("button");
const chatBox = document.querySelector(".chat-box .chat .wrapper");
let sender = document.querySelector(".sender").value;
let receiver = document.querySelector(".receiver").value;

// تهيئة Pusher
const pusher = new Pusher('c5b7459597ed992dd9c9', {
    cluster: 'ap2'
});

// الاشتراك في القناة
const channel = pusher.subscribe('chat-channel');

// استقبال الرسائل الجديدة
channel.bind('new_message', function(data) {
    if (data.senderId == sender) {
        chatBox.innerHTML += `<div class='box sent'>
                                <div class='details'>
                                    <p>${data.message}</p>
                                </div>
                            </div>`;
    } else {
        chatBox.innerHTML += `<div class='box received'>
                                <div class='details'>
                                    <p>${data.message}</p>
                                </div>
                                <div class='img'>
                                    <i class='fa-solid fa-user'></i>
                                </div>
                            </div>`;
    }
    scrollToBottom();
});

// إرسال الرسائل إلى الخادم باستخدام AJAX
sendBtn.onclick = () => {
    let message = inputField.value;
    sendBtn.classList.remove("active");
    const messageObject = {
        type: "sendMessages",
        senderId: sender,
        receiverId: receiver,
        message: message
    };

    // إنشاء طلب XMLHttpRequest
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "server.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    // عند اكتمال الطلب
    xhr.onload = function() {
        if (xhr.status === 200) {
            inputField.value = "";
            scrollToBottom();
        }
    };

    // إرسال الطلب
    xhr.send(JSON.stringify(messageObject));
};

form.onsubmit = (e) => {
    e.preventDefault();
};

inputField.focus();
inputField.onkeyup = () => {
    if (inputField.value !== "") {
        sendBtn.classList.add("active");
    } else {
        sendBtn.classList.remove("active");
    }
};

// تحميل الرسائل السابقة باستخدام AJAX
window.onload = () => {
    const fetchMessages = {
        type: "fetchMessages",
        senderId: sender,
        receiverId: receiver
    };

    // إنشاء طلب XMLHttpRequest
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "server.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    // عند اكتمال الطلب
    xhr.onload = function() {
        if (xhr.status === 200) {
            const messages = JSON.parse(xhr.responseText);
            chatBox.innerHTML = "";
            messages.forEach(message => {
                if (message.sender == sender) {
                    chatBox.innerHTML += `<div class='box sent'>
                                            <div class='details'>
                                                <p>${message.message}</p>
                                            </div>
                                        </div>`;
                } else {
                    chatBox.innerHTML += `<div class='box received'>
                                            <div class='details'>
                                                <p>${message.message}</p>
                                            </div>
                                            <div class='img'>
                                                <i class='fa-solid fa-user'></i>
                                            </div>
                                        </div>`;
                }
            });
            scrollToBottom();
        }
    };

    // إرسال الطلب
    xhr.send(JSON.stringify(fetchMessages));
};

// وظيفة تمرير الدردشة للأسفل
function scrollToBottom() {
    chatBox.parentElement.scrollTop = chatBox.parentElement.scrollHeight;
}
