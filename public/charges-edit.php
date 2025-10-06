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
    <div class="container-xl px-1">
        <div class="page-header-content pt-4">
            <div class="row align-items-center justify-content-between">
                <h4>Transport Charges</h4>
            </div>
        </div>
    </div>
    <table style="width:100%;">
        <tr>
            <td style="padding:10px;">
                <label for="removal_charge">Removal Charge</label><br>
                <input type="number" name="removal_charge" id="removal_charge" class="form-control" style="width:95%;" step="0.01" min="0" value="<?= htmlspecialchars($removal_charge ?? '') ?>">
            </td>
            <td style="padding:10px;">
                <label for="pouch_charge">Pouch Charge</label><br>
                <input type="number" name="pouch_charge" id="pouch_charge" class="form-control" style="width:95%;" step="0.01" min="0" value="<?= htmlspecialchars($pouch_charge ?? '') ?>">
            </td>
            <td style="padding:10px;">
                <label for="embalming_charge">Embalming Charge</label><br>
                <input type="number" name="embalming_charge" id="embalming_charge" class="form-control" style="width:95%;" step="0.01" min="0" value="<?= htmlspecialchars($embalming_charge ?? '') ?>">
            </td>
            <td style="padding:10px;">
                <label for="transport_fees">Transport Fees</label><br>
                <input type="number" name="transport_fees" id="transport_fees" class="form-control" style="width:95%;" step="0.01" min="0" value="<?= htmlspecialchars($transport_fees ?? '') ?>">
            </td>
        </tr>
        <tr>
            <td style="padding:10px;">
                <label for="cremation_charge">Cremation Charge</label><br>
                <input type="number" name="cremation_charge" id="cremation_charge" class="form-control" style="width:95%;" step="0.01" min="0" value="<?= htmlspecialchars($cremation_charge ?? '') ?>">
            </td>
            <td style="padding:10px;">
                <label for="wait_charge">Wait Charge</label><br>
                <input type="number" name="wait_charge" id="wait_charge" class="form-control" style="width:95%;" step="0.01" min="0" value="<?= htmlspecialchars($wait_charge ?? '') ?>">
            </td>
            <td style="padding:10px;">
                <label for="mileage_fees">Mileage Fees</label><br>
                <input type="number" name="mileage_fees" id="mileage_fees" class="form-control" style="width:95%;" step="0.01" min="0" value="<?= htmlspecialchars($mileage_fees ?? '') ?>">
            </td>
            <td></td>
        </tr>
        <tr>
            <td style="padding:10px;">
                <label for="other_charge_1">Other Charge 1</label><br>
                <input type="number" name="other_charge_1" id="other_charge_1" class="form-control" style="width:95%;" step="0.01" min="0" value="<?= htmlspecialchars($other_charge_1 ?? '') ?>">
                <input type="text" name="other_charge_1_description" id="other_charge_1_description" class="form-control mt-1" style="width:95%;" placeholder="Description" value="<?= htmlspecialchars($other_charge_1_description ?? '') ?>">
            </td>
            <td style="padding:10px;">
                <label for="other_charge_2">Other Charge 2</label><br>
                <input type="number" name="other_charge_2" id="other_charge_2" class="form-control" style="width:95%;" step="0.01" min="0" value="<?= htmlspecialchars($other_charge_2 ?? '') ?>">
                <input type="text" name="other_charge_2_description" id="other_charge_2_description" class="form-control mt-1" style="width:95%;" placeholder="Description" value="<?= htmlspecialchars($other_charge_2_description ?? '') ?>">
            </td>
            <td style="padding:10px;">
                <label for="other_charge_3">Other Charge 3</label><br>
                <input type="number" name="other_charge_3" id="other_charge_3" class="form-control" style="width:95%;" step="0.01" min="0" value="<?= htmlspecialchars($other_charge_3 ?? '') ?>">
                <input type="text" name="other_charge_3_description" id="other_charge_3_description" class="form-control mt-1" style="width:95%;" placeholder="Description" value="<?= htmlspecialchars($other_charge_3_description ?? '') ?>">
            </td>
            <td style="padding:10px;">
                <label for="other_charge_4">Other Charge 4</label><br>
                <input type="number" name="other_charge_4" id="other_charge_4" class="form-control" style="width:95%;" step="0.01" min="0" value="<?= htmlspecialchars($other_charge_4 ?? '') ?>">
                <input type="text" name="other_charge_4_description" id="other_charge_4_description" class="form-control mt-1" style="width:95%;" placeholder="Description" value="<?= htmlspecialchars($other_charge_4_description ?? '') ?>">
            </td>
        </tr>
        <tr>
            <td style="padding:10px;">
                <label for="total_charge">Total Charge</label><br>
                <input type="number" name="total_charge" id="total_charge" class="form-control" style="width:95%;" step="0.01" min="0" value="<?= htmlspecialchars($total_charge ?? '') ?>">
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
</div>
</html>
