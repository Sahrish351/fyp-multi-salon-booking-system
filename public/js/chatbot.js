(function () {
    'use strict';
 
    const CHAT_ENDPOINT = '/chatbot/ask';
 
 
    const QUICK_REPLIES = [
        {
            label: 'Book Appointment', sub: 'How do I book a service?',
            query: 'How do I book an appointment?',
            instant: "Booking is simple! Choose a salon, pick your service, select a stylist, then a date & time, and confirm with payment. It takes less than 2 minutes."
        },
        {
            label: 'Salon Owner?', sub: 'List your salon with us',
            query: 'How do I list my salon?',
            instant: "Fill in your salon details to register — once our Admin reviews and approves it, you'll get access to your own dashboard to manage services, stylists, time slots, bookings, and payments."
        },
        {
            label: 'Pricing', sub: 'Is it free to use?',
            query: 'Is Beauty Blush Salons free?',
            instant: "Browsing salons is completely free! Booking just needs a Rs. 100 advance to confirm your slot — the rest is paid after your service."
        },
        {
            label: 'How It Works', sub: 'See the full flow',
            query: 'How does it work?',
            instant: "Browse salons → Pick a service & stylist → Choose date/time → Pay securely → Get instant confirmation. That's it!"
        }
    ];
 
    /* ---------------------------------------------------------
       DOM REFERENCES
    --------------------------------------------------------- */
    const widget      = document.getElementById('bb-chat-widget');
    const launcher     = document.getElementById('bb-chat-launcher');
    const messagesBox  = document.getElementById('bb-chat-messages');
    const quickBox     = document.getElementById('bb-quick-replies');
    const input        = document.getElementById('bb-chat-input');
    const sendBtn      = document.getElementById('bb-btn-send');
    const micBtn       = document.getElementById('bb-btn-mic');
    const muteBtn      = document.getElementById('bb-btn-mute');
    const clearBtn     = document.getElementById('bb-btn-clear');
    const themeBtn     = document.getElementById('bb-btn-theme');
    const minimizeBtn  = document.getElementById('bb-btn-minimize');
 
    let voiceEnabled = true;
    let hasGreeted = false;
 
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }
 
    /* ---------------------------------------------------------
       OPEN / CLOSE / MINIMIZE
    --------------------------------------------------------- */
    launcher.addEventListener('click', () => {
        widget.classList.toggle('bb-open');
        widget.classList.remove('bb-minimized');
        if (widget.classList.contains('bb-open') && !hasGreeted) {
            greet();
            hasGreeted = true;
        }
    });
 
    minimizeBtn.addEventListener('click', () => {
        widget.classList.remove('bb-open');
    });
 
    function greet() {
        addBotMessage("Hey there! I'm Bella — your Beauty Blush Salons assistant. Ask me anything about booking, salons, pricing, or how everything works!");
        renderQuickReplies();
    }
 
    /* ---------------------------------------------------------
       CLEAR CHAT
    --------------------------------------------------------- */
    clearBtn.addEventListener('click', () => {
        if ('speechSynthesis' in window) window.speechSynthesis.cancel();
        messagesBox.innerHTML = '';
        quickBox.innerHTML = '';
        hasGreeted = false;
        greet();
    });
 
    /* ---------------------------------------------------------
       THEME TOGGLE
    --------------------------------------------------------- */
    themeBtn.addEventListener('click', () => {
        const isDark = widget.getAttribute('data-theme') === 'dark';
        widget.setAttribute('data-theme', isDark ? 'light' : 'dark');
    });
 
    /* ---------------------------------------------------------
       VOICE OUTPUT TOGGLE
    --------------------------------------------------------- */
    muteBtn.addEventListener('click', () => {
        voiceEnabled = !voiceEnabled;
        muteBtn.querySelector('.bb-svg-sound-on').style.display = voiceEnabled ? '' : 'none';
        muteBtn.querySelector('.bb-svg-sound-off').style.display = voiceEnabled ? 'none' : '';
        if (!voiceEnabled && 'speechSynthesis' in window) window.speechSynthesis.cancel();
    });
 
    /* ---------------------------------------------------------
       QUICK REPLIES
    --------------------------------------------------------- */
    function renderQuickReplies() {
        quickBox.innerHTML = '';
        QUICK_REPLIES.forEach(item => {
            const btn = document.createElement('button');
            btn.className = 'bb-quick-btn';
            btn.innerHTML = `<strong>${item.label}</strong>${item.sub}`;
            btn.addEventListener('click', () => {
                addUserMessage(item.query);
                quickBox.innerHTML = ''; // quick replies used up — they don't come back
                showTyping();
                // Instant local reply for known quick-reply topics — no API call, saves free quota.
                setTimeout(() => {
                    hideTyping();
                    addBotMessage(item.instant);
                }, 300);
            });
            quickBox.appendChild(btn);
        });
    }
 
    /* ---------------------------------------------------------
       MESSAGE RENDERING
    --------------------------------------------------------- */
    function timeNow() {
        return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }
 
    function addUserMessage(text) {
        const div = document.createElement('div');
        div.className = 'bb-msg bb-msg-user';
        div.innerHTML = `${escapeHtml(text)}<span class="bb-msg-time">${timeNow()}</span>`;
        messagesBox.appendChild(div);
        scrollToBottom();
    }
 
    function addBotMessage(text) {
        const div = document.createElement('div');
        div.className = 'bb-msg bb-msg-bot';
        div.innerHTML = `${escapeHtml(text)}<span class="bb-msg-time">${timeNow()}</span>`;
        messagesBox.appendChild(div);
        scrollToBottom();
        speak(text);
    }
 
    function showTyping() {
        const div = document.createElement('div');
        div.className = 'bb-typing';
        div.id = 'bb-typing-indicator';
        div.innerHTML = '<span></span><span></span><span></span>';
        messagesBox.appendChild(div);
        scrollToBottom();
    }
 
    function hideTyping() {
        const el = document.getElementById('bb-typing-indicator');
        if (el) el.remove();
    }
 
    function scrollToBottom() {
        messagesBox.scrollTop = messagesBox.scrollHeight;
    }
 
    function escapeHtml(str) {
        const d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }
 
    /* ---------------------------------------------------------
       SEND FLOW — typed/spoken messages go to the real AI
    --------------------------------------------------------- */
    async function handleUserMessage(text) {
        const trimmed = text.trim();
        if (!trimmed) return;
 
        addUserMessage(trimmed);
        input.value = '';
        quickBox.innerHTML = '';
        showTyping();
 
        try {
            const res = await fetch(CHAT_ENDPOINT, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: trimmed })
            });
 
            const data = await res.json();
            hideTyping();
            addBotMessage(data.reply || "Sorry, I couldn't process that — please try again.");
        } catch (err) {
            hideTyping();
            addBotMessage("I'm having trouble connecting right now — please check your internet and try again.");
        }
    }
 
    sendBtn.addEventListener('click', () => handleUserMessage(input.value));
    input.addEventListener('keydown', e => {
        if (e.key === 'Enter') handleUserMessage(input.value);
    });
 
    /* ---------------------------------------------------------
       VOICE OUTPUT - free, built into the browser
       Bella gets a female voice: we cache the browser's available
       voices (they load asynchronously) and pick a female-sounding
       one by name whenever we speak.
    --------------------------------------------------------- */
    let cachedVoices = [];
 
    function loadVoices() {
        cachedVoices = window.speechSynthesis.getVoices();
    }
 
    if ('speechSynthesis' in window) {
        loadVoices();
        window.speechSynthesis.onvoiceschanged = loadVoices;
    }
 
    function getFemaleVoice() {
        if (!cachedVoices.length) return null;
        // Known female voice names across Chrome, Edge, Safari, mobile browsers
        const femalePattern = /female|zira|samantha|susan|karen|hazel|moira|tessa|fiona|victoria|google us english|google uk english female/i;
        return cachedVoices.find(v => femalePattern.test(v.name)) || null;
    }
 
    function speak(text) {
        if (!voiceEnabled || !('speechSynthesis' in window)) return;
        window.speechSynthesis.cancel();
        const utter = new SpeechSynthesisUtterance(text);
        utter.rate = 1;
        utter.pitch = 1.1;
        utter.lang = 'en-US';
 
        const femaleVoice = getFemaleVoice();
        if (femaleVoice) {
            utter.voice = femaleVoice;
        }
 
        window.speechSynthesis.speak(utter);
    }
 
    /* ---------------------------------------------------------
       VOICE INPUT (mic) - free, built into the browser
    --------------------------------------------------------- */
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    let recognizer = null;
    let isListening = false;
 
    if (SpeechRecognition) {
        recognizer = new SpeechRecognition();
        recognizer.continuous = false;
        recognizer.interimResults = false;
        recognizer.lang = 'en-US';
 
        recognizer.onstart = () => {
            isListening = true;
            micBtn.classList.add('bb-mic-active');
            input.placeholder = 'Listening...';
        };
        recognizer.onend = () => {
            isListening = false;
            micBtn.classList.remove('bb-mic-active');
            input.placeholder = 'Ask Bella anything...';
        };
        recognizer.onerror = () => {
            isListening = false;
            micBtn.classList.remove('bb-mic-active');
            input.placeholder = 'Ask Bella anything...';
        };
        recognizer.onresult = (event) => {
            const transcript = event.results[0][0].transcript;
            handleUserMessage(transcript);
        };
 
        micBtn.addEventListener('click', () => {
            if (isListening) recognizer.stop();
            else recognizer.start();
        });
    } else {
        micBtn.addEventListener('click', () => {
            addBotMessage("Voice input isn't supported in this browser — try Chrome or Edge for the mic feature, or just type your question!");
        });
    }
})();
 