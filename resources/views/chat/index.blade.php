<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>ChatBot</title>
<style>
body { font-family: Arial; background:#f7f7f7; margin:0; padding:0; }
.chat-container { max-width:700px; margin:30px auto; background:white; border-radius:8px; padding:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
.messages { min-height:400px; overflow-y:auto; margin-bottom:15px; }
.msg { margin:10px 0; padding:10px 14px; border-radius:14px; max-width:80%; }
.user { background:#d1e7dd; align-self:flex-end; float:right; clear:both; }
.assistant { background:#e9ecef; float:left; clear:both; }
textarea { width:100%; height:60px; border-radius:8px; padding:10px; border:1px solid #ccc; }
button { margin-top:10px; padding:10px 16px; border:none; background:#007bff; color:white; border-radius:6px; cursor:pointer; }
button:hover { background:#0069d9; }
</style>
</head>
<body>
<div class="chat-container">
<h2>ChatBot 🤖</h2>
<div id="chat" class="messages"></div>
<textarea id="message" placeholder="Type a message..."></textarea>
<button id="send">Send</button>
</div>

<script>
const sendBtn = document.getElementById('send');
const messageInput = document.getElementById('message');
const chatBox = document.getElementById('chat');
const csrf = document.querySelector('meta[name="csrf-token"]').content;

function append(role, text) {
    const div = document.createElement('div');
    div.className = 'msg ' + role;
    div.innerHTML = text.replace(/\n/g, '<br>');
    chatBox.appendChild(div);
    chatBox.scrollTop = chatBox.scrollHeight;
}

async function sendMessage() {
    const text = messageInput.value.trim();
    if (!text) return;
    append('user', text);
    messageInput.value = '';
    append('assistant', 'Typing...');

    try {
        const response = await fetch('{{ route("chat.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify({ message: text }),
        });

        const data = await response.json();
        chatBox.lastChild.remove(); // remove "Typing..."
        if (data.assistant) {
            append('assistant', data.assistant);
        } else {
            append('assistant', 'Error: ' + (data.error || 'No response'));
        }
    } catch (err) {
        chatBox.lastChild.remove();
        append('assistant', 'Network error: ' + err.message);
    }
}

sendBtn.addEventListener('click', sendMessage);
messageInput.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});
</script>
</body>
</html>
