<?php
// charges-edit.php
// This page will be used as a section in transport-edit.php for editing/adding transport charges.
?>
<!DOCTYPE html>
<html lang="en">
<div class="container-xl px-1">
    <div class="page-header-content pt-4">
        <div class="row align-items-center justify-content-between">
            <h4>Transport Charges</h4>
        </div>
    </div>
</div>
<div id="charges-section">
    <table style="width:100%;">
        <tr>
            <td style="padding:10px;">
                <label for="removal_charge">Removal Charge <span class="required" style="color:red;">*</span></label><br>
                <input type="number" name="removal_charge" id="removal_charge" class="form-control" style="width:95%;" step="0.01" min="0" required value="<?= htmlspecialchars($charges['removal_charge'] ?? '') ?>">
            </td>
            <td style="padding:10px;">
                <label for="pouch_charge">Pouch Charge <span class="required" style="color:red;">*</span></label><br>
                <input type="number" name="pouch_charge" id="pouch_charge" class="form-control" style="width:95%;" step="0.01" min="0" required value="<?= htmlspecialchars($charges['pouch_charge'] ?? '') ?>">
            </td>
            <td style="padding:10px;">
                <label for="embalming_charge">Embalming Charge <span class="required" style="color:red;">*</span></label><br>
                <input type="number" name="embalming_charge" id="embalming_charge" class="form-control" style="width:95%;" step="0.01" min="0" required value="<?= htmlspecialchars($charges['embalming_charge'] ?? '') ?>">
            </td>
            <td style="padding:10px;">
                <label for="cremation_charge">Cremation Charge <span class="required" style="color:red;">*</span></label><br>
                <input type="number" name="cremation_charge" id="cremation_charge" class="form-control" style="width:95%;" step="0.01" min="0" required value="<?= htmlspecialchars($charges['cremation_charge'] ?? '') ?>">
            </td>
        </tr>
    </table>
    <div id="charges-error" style="color:red; display:none; margin-top:10px;"></div>
</div>
</html>
