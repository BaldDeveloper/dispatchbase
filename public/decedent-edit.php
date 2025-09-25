<?php
// decedent-edit.php
// This page will be used for adding/editing decedent records. Currently empty, but includes a label for testing inclusion.
?>
<div id="decedent-section">
    <h4>Decedent Information</h4>
    <table style="width:100%;">
        <tr>
            <td style="padding:10px;">
                <label for="first_name">First Name<span style="color:red;">*</span></label><br>
                <input type="text" id="first_name" name="first_name" class="form-control" required style="width:95%;" value="<?= htmlspecialchars($decedentFirstName ?? '') ?>">
            </td>
            <td style="padding:10px;">
                <label for="last_name">Last Name<span style="color:red;">*</span></label><br>
                <input type="text" id="last_name" name="last_name" class="form-control" required style="width:95%;" value="<?= htmlspecialchars($decedentLastName ?? '') ?>">
            </td>
        </tr>
    </table>
    <!-- Add other fields as needed -->
</div>
