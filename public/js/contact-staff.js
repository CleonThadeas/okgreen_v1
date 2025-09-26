document.addEventListener("DOMContentLoaded", () => {
    const chatBody = document.querySelector(".chat-body");
    if(chatBody){
        chatBody.scrollTop = chatBody.scrollHeight; // auto scroll ke bawah
    }
});
