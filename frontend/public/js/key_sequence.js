const listener = new window.keypress.Listener();
let recording = false;
const expectedSequence = "siteadmin".split('');
let currentIndex = 0;
let timeoutId = null;
let capsLockOn = false;
const timeoutDuration = 15 * 1000; // 15 seconds

// Track CapsLock state
document.addEventListener('keydown', function (event) {
    capsLockOn = event.getModifierState('CapsLock');

    // If recording is active, track sequence here
    if (recording && event.key.length === 1) {
        const pressedChar = event.key.toLowerCase();
        const expectedChar = expectedSequence[currentIndex];

        if (pressedChar === expectedChar) {
            currentIndex++;
            // console.log(`Correct key: ${pressedChar} Progress: ${currentIndex}/${expectedSequence.length}`);

            if (currentIndex === expectedSequence.length) {
                recording = false;
                currentIndex = 0;
                if (timeoutId) clearTimeout(timeoutId);
                const event = new CustomEvent('admin-login');
                window.dispatchEvent(event);
            }
        } else {
            // console.log(`Wrong key: ${pressedChar} (expected ${expectedChar}) - Resetting`);
            currentIndex = 0;
        }
    }
});

// Combo to start recording (CapsLock + Ctrl + Alt)
listener.register_combo({
    "keys": "ctrl alt",
    "on_keydown": function () {
        if (!capsLockOn) return;
        if (window.location.href.includes('admin')) return; // Don't start if already on admin page
        if (!recording) {
            recording = true;
            currentIndex = 0;
            // console.log(`Recording started - you have ${timeoutDuration / 1000} seconds to type 'siteadmin'`);

            // Clear any existing timeout
            if (timeoutId) clearTimeout(timeoutId);

            timeoutId = setTimeout(function () {
                if (recording) {
                    // console.log("Time's up!");
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
            // console.log("Recording cancelled by user");
            recording = false;
            currentIndex = 0;
            if (timeoutId) clearTimeout(timeoutId);
        }
    }
});

// console.log("Secret sequence armed. Hold Ctrl+Alt with CapsLock ON to start typing 'siteadmin'.");