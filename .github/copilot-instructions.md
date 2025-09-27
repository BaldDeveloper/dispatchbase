# Copilot Instructions: Field Formatting and Validation

**Standardization Requirement:**

For all pages in this project, any field for phone number, email, or US state must have the exact same formatting and validation behavior as implemented on the customer page (`customer-edit.php`).

## Phone Number Fields
- Use the same input pattern, maxlength, and autocomplete attributes as on the customer page.
- Include the phone-format.js script to provide live formatting as the user types.
- Apply the same JavaScript and server-side validation logic for phone numbers.

## Email Fields
- Use the same input pattern for email validation as on the customer page.
- Apply the same JavaScript and server-side validation logic for emails.

## US State Fields
- Use a dropdown/select for US states, populated from the same source as the customer page.
- Validate server-side to ensure only valid state abbreviations are accepted.

## General
- Any new or existing page with these fields must match the customer page's user experience and validation for these fields exactly.
- If the customer page is updated, all other pages must be updated to match.

