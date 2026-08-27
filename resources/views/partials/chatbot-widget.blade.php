 
<link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
 
<div id="bb-chat-widget" class="bb-chat-widget" data-theme="light">
 
    {{-- Floating Launcher Button --}}
    <button id="bb-chat-launcher" class="bb-chat-launcher" aria-label="Open Bella, your salon assistant">
        <span class="bb-launcher-icon bb-icon-chat">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 4h16v12H7l-3 3V4z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="9" cy="10" r="1" fill="currentColor"/>
                <circle cx="12" cy="10" r="1" fill="currentColor"/>
                <circle cx="15" cy="10" r="1" fill="currentColor"/>
            </svg>
        </span>
        <span class="bb-launcher-icon bb-icon-close">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
        </span>
        <span class="bb-launcher-pulse"></span>
    </button>
 
    {{-- Chat Panel --}}
    <div id="bb-chat-panel" class="bb-chat-panel" role="dialog" aria-label="Bella chat assistant">
 
        {{-- Header --}}
        <div class="bb-chat-header">
            <div class="bb-chat-avatar">
                <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20" cy="20" r="20" fill="url(#bb-avatar-grad)"/>
                    <path d="M20 11c-4 0-6.5 3-6.5 6.5 0 2 .9 3.6 2.1 4.7-2.6 1.1-4.6 3.4-5.1 6.3h19c-.5-2.9-2.5-5.2-5.1-6.3 1.2-1.1 2.1-2.7 2.1-4.7 0-3.5-2.5-6.5-6.5-6.5z" fill="#fff" fill-opacity=".9"/>
                    <defs>
                        <linearGradient id="bb-avatar-grad" x1="0" y1="0" x2="40" y2="40" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#C9A15A"/>
                            <stop offset="1" stop-color="#4A1E2B"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>
            <div class="bb-chat-headinfo">
                <h4>Bella <span class="bb-chat-status-dot"></span></h4>
                <p>Beauty Blush Assistant &bull; Online</p>
            </div>
            <div class="bb-chat-header-actions">
                <button id="bb-btn-mute" class="bb-icon-btn" title="Toggle voice replies" aria-pressed="true">
                    <svg class="bb-svg-sound-on" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 9v6h4l5 4V5L8 9H4z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                        <path d="M16.5 8.5a5 5 0 010 7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                        <path d="M19 6.5a9 9 0 010 11" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    </svg>
                    <svg class="bb-svg-sound-off" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:none">
                        <path d="M4 9v6h4l5 4V5L8 9H4z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                        <path d="M16 9l5 6M21 9l-5 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    </svg>
                </button>
                <button id="bb-btn-clear" class="bb-icon-btn" title="Clear chat">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 7h16M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m2 0v13a1 1 0 01-1 1H8a1 1 0 01-1-1V7h10z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <button id="bb-btn-theme" class="bb-icon-btn" title="Toggle light / dark">
                    <svg class="bb-svg-moon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 14.5A8.5 8.5 0 119.5 4 6.8 6.8 0 0020 14.5z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                    </svg>
                </button>
                <button id="bb-btn-minimize" class="bb-icon-btn" title="Minimize">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
        </div>
 
        {{-- Messages --}}
        <div id="bb-chat-messages" class="bb-chat-messages"></div>
 
        {{-- Quick Replies (shown at start) --}}
        <div id="bb-quick-replies" class="bb-quick-replies"></div>
 
        {{-- Input --}}
        <div class="bb-chat-input-row">
            <button id="bb-btn-mic" class="bb-icon-btn bb-mic-btn" title="Speak your question">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="9" y="3" width="6" height="11" rx="3" stroke="currentColor" stroke-width="1.6"/>
                    <path d="M5 11a7 7 0 0014 0" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    <path d="M12 18v3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
            </button>
            <input type="text" id="bb-chat-input" placeholder="Ask Bella anything..." autocomplete="off">
            <button id="bb-btn-send" class="bb-send-btn" title="Send">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 12l16-8-6 8 6 8-16-8z" fill="currentColor"/>
                </svg>
            </button>
        </div>
    </div>
</div>
 
<script src="{{ asset('js/chatbot.js') }}"></script>
 