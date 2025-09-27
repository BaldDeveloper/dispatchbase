<?php
// firm-edit.php
// This page will be used for adding/editing firm records. Currently empty, but includes a label for testing inclusion.
?>
<style>
    .required::after {
        content: " *";
        color: red;
    }
</style>
<div id="firm-section">
    <h4>Firm Information</h4>
    <table class="table table-borderless">
        <tr>
            <td>
                <div class="mb-3">
                    <label for="firm_date" class="form-label required">Firm Date</label>
                    <input type="date" class="form-control" id="firm_date" name="firm_date" required value="<?= htmlspecialchars($firmDate ?? '') ?>" />
                </div>
            </td>
            <td>
                <div class="mb-3">
                    <label for="firm_id" class="form-label required">Firm</label>
                    <select class="form-control" id="firm_id" name="firm_id" required>
                        <option value="">Select Firm</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?= htmlspecialchars($customer['customer_number']) ?>" <?= ($firmId == $customer['customer_number']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($customer['company_name']) ?> (<?= htmlspecialchars($customer['customer_number']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="mb-3">
                    <label for="firm_account_type" class="form-label required">Firm Account Type</label>
                    <input type="text" class="form-control" id="firm_account_type" name="firm_account_type" maxlength="50" required value="<?= htmlspecialchars($firmAccountType ?? '') ?>" />
                </div>
            </td>
            <td>
                <!-- Placeholder for future field -->
            </td>
        </tr>
    </table>
    <!-- Add other fields as needed -->
</div>
