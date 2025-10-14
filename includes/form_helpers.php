<?php
/**
 * Render a Bootstrap invalid-feedback element for form validation
 *
 * @param string $message The error message to display
 * @param bool $show Whether to show the feedback (field is invalid)
 */
function render_invalid_feedback($message, $show) {
    if ($show) {
        echo '<div class="invalid-feedback d-block">' . htmlspecialchars($message) . '</div>';
    }
}

