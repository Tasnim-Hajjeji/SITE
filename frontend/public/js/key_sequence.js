const listener = new window.keypress.Listener();
let recording = false;
const expectedSequence = "siteadmin".split('');
let currentIndex = 0;
let timeoutId = null;
let capsLockOn = false;
const timeoutDuration = 15 * 1000; // 15 seconds

// Track CapsLock state
document.addEventListener('keydown', function (event) {
    if (typeof event.getModifierState !== 'function') return;
    capsLockOn = event.getModifierState('CapsLock');

    // If recording is active, track sequence here
    if (recording && event.key.length === 1) {
        const pressedChar = event.key.toLowerCase();
        const expectedChar = expectedSequence[currentIndex];

        if (pressedChar === expectedChar) {
            currentIndex++;

            if (currentIndex === expectedSequence.length) {
                recording = false;
                currentIndex = 0;
                if (timeoutId) clearTimeout(timeoutId);

                // Dispatch event to show login modal
                const event = new CustomEvent('show-admin-login');
                window.dispatchEvent(event);
            }
        } else {
            currentIndex = 0;
        }
    }
});

// Combo to start recording (CapsLock + Ctrl + Alt)
listener.register_combo({
    "keys": "ctrl alt",
    "on_keydown": function () {
        if (!capsLockOn) return;
        if (!recording) {
            recording = true;
            currentIndex = 0;

            // Clear any existing timeout
            if (timeoutId) clearTimeout(timeoutId);

            timeoutId = setTimeout(function () {
                if (recording) {
                    recording = false;
                    currentIndex = 0;
                }
            }, timeoutDuration);
        }
    },
    "prevent_default": true
});

// Cancel with Escape
listener.register_combo({
    "keys": "esc",
    "on_keydown": function () {
        if (recording) {
            recording = false;
            currentIndex = 0;
            if (timeoutId) clearTimeout(timeoutId);
        }
    }
});