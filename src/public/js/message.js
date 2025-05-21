const itemId = document.getElementById('chat-container').dataset.itemId;

const messageInput = document.querySelector('.input-message');

messageInput.addEventListener('input', () => {
    sessionStorage.setItem(`chatMessage_${itemId}`, messageInput.value);
});

window.addEventListener('load', () => {
    const savedMessage = sessionStorage.getItem(`chatMessage_${itemId}`);
    if (savedMessage) {
        document.querySelector('.input-message').value = savedMessage;
    }
});

const form = document.querySelector('.chat_progressing-inputs');
form.addEventListener('submit', () => {
    sessionStorage.removeItem(`chatMessage_${itemId}`);
});