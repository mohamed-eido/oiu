
showChannels();

// إظهار البيانات عند الضغط على زر القناة
function showChannels() {
    // طلب البيانات من PHP باستخدام AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'assets/ps/get_channel.php?id=' + 0, true);
    xhr.onload = function() {
        if (this.status === 200) {
            const channelData = JSON.parse(this.responseText);

            let channel = "";

            channelData.forEach(element => {
                channel += `
                    <div class="file" onclick="showChannelData(${element.id})">
                        <span class="title">${element.channel_name}</span>
                        <i class="fa-solid fa-angle-left"></i>
                    </div>
                `;
            });

            document.getElementById('files-list').innerHTML = channel;
        }
    };
    xhr.send();
}


const blur = document.querySelector(".blur2");

function showChannelData(channelId) {
    // طلب البيانات من PHP باستخدام AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'assets/ps/get_channel.php?id=' + channelId, true);
    xhr.onload = function() {
        if (this.status === 200) {
            const channelData = JSON.parse(this.responseText);

            let modal = document.getElementById('modal');

            let modalContent = `
            <i class="fa-solid fa-times" onclick="toggleModal()"></i>
            <p>اسم المادة: <span id="title">${channelData.channel_name}</span></p>
            <p>اسم المحاضر: <span id="teacher">${channelData.teacher_name}</span></p>
            <p>رابط القناة: <a href="${channelData.channel_link}" target="_blank">${channelData.channel_link}</a></p>
            `;

            modal.innerHTML = modalContent;
            setTimeout(() => {
                blur.classList.toggle("active");
                modal.classList.toggle("active");
            }, 1000);
        }
    };
    xhr.send();
}

// إغلاق النافذة المنبثقة
function toggleModal() {
    document.getElementById('modal').classList.toggle("active");
    blur.classList.toggle("active");
}