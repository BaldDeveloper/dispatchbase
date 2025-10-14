// Utility functions for form reset and error display

/**
 * Reset all fields in a form and clear validation states.
 * @param {HTMLFormElement} form
 */
function resetForm(form) {
    form.reset();
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.style.display = 'none');
}

/**
 * Show validation error for a field.
 * @param {HTMLElement} field
 * @param {string} message
 */
function showFieldError(field, message) {
    field.classList.add('is-invalid');
    let feedback = field.parentElement.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.textContent = message;
        feedback.style.display = 'block';
    }
}

/**
 * Hide validation error for a field.
 * @param {HTMLElement} field
 */
function hideFieldError(field) {
    field.classList.remove('is-invalid');
    let feedback = field.parentElement.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.style.display = 'none';
    }
}

// Export functions if using modules
if (typeof module !== 'undefined') {
    module.exports = { resetForm, showFieldError, hideFieldError };
}
