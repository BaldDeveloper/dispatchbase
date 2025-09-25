// phone-format.js
// Auto-format phone number fields as (000)000-0000

function formatPhoneNumber(value) {
    // Remove all non-digit characters
    const digits = value.replace(/\D/g, '');
    let formatted = '';
    if (digits.length > 0) {
        formatted += '(' + digits.substring(0, 3);
    }
    if (digits.length >= 4) {
        formatted += ')' + digits.substring(3, 6);
    }
    if (digits.length >= 7) {
        formatted += '-' + digits.substring(6, 10);
    }
    return formatted;
}

function attachPhoneFormatter(selector = '.phone-number') {
    document.querySelectorAll(selector).forEach(function(input) {
        input.addEventListener('input', function(e) {
            const cursorPos = input.selectionStart;
            const oldValue = input.value;
            let formatted = formatPhoneNumber(input.value);
            input.value = formatted;
            // Try to keep cursor position
            if (document.activeElement === input) {
                let newPos = cursorPos;
                if (formatted.length > oldValue.length) newPos++;
                input.setSelectionRange(newPos, newPos);
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    attachPhoneFormatter('#phone_number, .phone-number');
});

