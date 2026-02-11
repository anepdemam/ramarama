<div class="rama-chat-container">
    <!-- Rama Orb -->
    <div class="rama-orb" id="ramaOrb">
        <i class="fa-solid fa-comment-dots"></i>
    </div>

    <!-- Chat Window -->
    <div class="rama-window" id="ramaWindow">
        <div class="rama-header">
            <div class="avatar">R</div>
            <div class="info">
                <h4>Rama</h4>
                <span>Always Online</span>
            </div>
            <div class="close-chat" id="closeChat">
                <i class="fa-solid fa-xmark"></i>
            </div>
        </div>

        <div class="rama-messages" id="chatMessages">
            <div class="msg bot">
                Hi! I'm Rama, your personal style guide. Ready to break some boundaries today? How can I assist with
                your collection?
            </div>
        </div>

        <div class="rama-input-area">
            <form id="chatForm">
                @csrf
                <div class="input-prefix">
                    <i class="fa-solid fa-sparkles"></i>
                </div>
                <input type="text" id="chatInput" placeholder="Ask Rama something..." autocomplete="off">
                <button type="submit" id="sendBtn">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const orb = document.getElementById('ramaOrb');
        const windowElem = document.getElementById('ramaWindow');
        const closeBtn = document.getElementById('closeChat');
        const form = document.getElementById('chatForm');
        const input = document.getElementById('chatInput');
        const messages = document.getElementById('chatMessages');
        const sendBtn = document.getElementById('sendBtn');

        // Toggle Chat
        orb.addEventListener('click', () => {
            windowElem.classList.add('active');
            orb.style.display = 'none'; // Hide orb when window is open
            input.focus();
        });

        // Close Chat
        closeBtn.addEventListener('click', () => {
            windowElem.classList.remove('active');
            orb.style.display = 'flex'; // Show orb when window is closed
        });

        // Handle Messages
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const text = input.value.trim();
            if (!text) return;

            // Append User Message
            addMessage(text, 'user');
            input.value = '';

            // Show Typing
            const typing = showTyping();

            try {
                const response = await fetch("{{ route('chat.message') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ message: text })
                });

                const data = await response.json();
                typing.remove();
                addMessage(data.response, 'bot');
            } catch (error) {
                typing.remove();
                addMessage("I'm having a little trouble connecting. Could you try again in a bit?", 'bot');
            }
        });

        function addMessage(text, type) {
            const div = document.createElement('div');
            div.className = `msg ${type}`;
            div.textContent = text;
            messages.appendChild(div);
            messages.scrollTop = messages.scrollHeight;
        }

        function showTyping() {
            const div = document.createElement('div');
            div.className = 'typing';
            div.innerHTML = '<span></span><span></span><span></span>';
            messages.appendChild(div);
            messages.scrollTop = messages.scrollHeight;
            return div;
        }
    });
</script>