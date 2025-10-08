<?php
require_once __DIR__ . '/../database/TransportData.php';
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../includes/auth.php';

// transit-edit_old.php
// This page will be used for adding/editing transit records. Currently empty, but includes a label for testing inclusion.
?>
<!DOCTYPE html>
<html lang="en">
<div class="container-xl px-1">
    <div class="page-header-content pt-4">
        <div class="row align-items-center justify-content-between">
            <h4>Transit Information</h4>
        </div>
    </div>
</div>
<!-- transit-edit_old.php: Pure view, expects $originLocations, $destinationLocations, $coroners, $pouchTypes, $originLocation, $destinationLocation, $coronerName, $transitPermitNumber, $tagNumber, $pouchType -->
<div id="transit-section">
    <table style="width:100%;">
        <tr>
            <td style="padding:10px;">
                <label for="origin_location" class="required">Origin Location<span style="color:red;">*</span></label><br>
                <select id="origin_location" name="origin_location" class="form-control" style="width:95%;" required>
                    <option value="">Select Origin Location</option>
                    <?php foreach ($originLocations as $origin): ?>
                        <option value="<?= htmlspecialchars($origin['id']) ?>" <?= (isset($originLocation) && $originLocation == $origin['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($origin['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Please fill out this field.</div>
            </td>
            <td style="padding:10px;">
                <label for="destination_location" class="required">Destination Location<span style="color:red;">*</span></label><br>
                <select id="destination_location" name="destination_location" class="form-control" style="width:95%;" required>
                    <option value="">Select Destination Location</option>
                    <?php foreach ($destinationLocations as $destination): ?>
                        <option value="<?= htmlspecialchars($destination['id']) ?>" <?= (isset($destinationLocation) && $destinationLocation == $destination['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($destination['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Please fill out this field.</div>
            </td>
            <td style="padding:10px;">
                <label for="coroner">Coroner<span style="color:red;">*</span></label><br>
                <select id="coroner" name="coroner" class="form-control" style="width:95%;">
                    <option value="" <?= empty($coronerName) ? 'selected' : '' ?>>Select Coroner</option>
                    <?php foreach ($coroners as $coroner): ?>
                        <option value="<?= htmlspecialchars($coroner['id'] ?? $coroner['coroner_number']) ?>" <?= (isset($coronerName) && $coronerName === $coroner['coroner_name']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($coroner['coroner_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td style="padding:10px;">
                <label for="transit_permit_number">Transit Permit Number</label><br>
                <input type="text" id="transit_permit_number" name="transit_permit_number" class="form-control" style="width:95%;" value="<?= htmlspecialchars($transitPermitNumber ?? '') ?>">
            </td>
            <td style="padding:10px;">
                <label for="tag_number">Tag Number<span style="color:red;">*</span></label><br>
                <input type="text" id="tag_number" name="tag_number" class="form-control<?= isset($error) && strpos($error, 'tag_number') !== false ? ' is-invalid' : '' ?>" style="width:95%;" value="<?= htmlspecialchars($tagNumber ?? '') ?>" required>
                <div class="invalid-feedback">Please fill out this field.</div>
            </td>
            <td style="padding:10px;">
                <label for="pouch_type">Pouch Type<span style="color:red;">*</span></label><br>
                <select id="pouch_type" name="pouch_type" class="form-control" style="width:95%;">
                    <option value="" <?= empty($pouchType) ? 'selected' : '' ?>>Select Pouch Type</option>
                    <?php foreach ($pouchTypes as $pouch): ?>
                        <?php $type = $pouch['pouch_type'] ?? $pouch['type'] ?? $pouch; ?>
                        <option value="<?= htmlspecialchars($type) ?>" <?= (isset($pouchType) && $pouchType === $type) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($type) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td style="padding:10px;">
                <label for="primary_transporter">Primary Transporter<span style="color:red;">*</span></label><br>
                <select id="primary_transporter" name="primary_transporter" class="form-control" style="width:95%;">
                    <option value="" <?= empty($primaryTransporter) ? 'selected' : '' ?>>Select Primary Transporter</option>
                    <?php foreach ($drivers as $driver): ?>
                        <option value="<?= htmlspecialchars($driver['id']) ?>" <?= (isset($primaryTransporter) && $primaryTransporter == $driver['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($driver['username']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td style="padding:10px;">
                <label for="assistant_transporter">Assistant Transporter</label><br>
                <select id="assistant_transporter" name="assistant_transporter" class="form-control" style="width:95%;">
                    <option value="" <?= empty($assistantTransporter) ? 'selected' : '' ?>>None</option>
                    <?php foreach ($drivers as $driver): ?>
                        <option value="<?= htmlspecialchars($driver['id']) ?>" <?= (isset($assistantTransporter) && $assistantTransporter == $driver['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($driver['username']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td></td>
        </tr>
    </table>
    <div id="transporter-error" style="color:red; display:none; margin-top:10px;"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Find the parent form
    var transitSection = document.getElementById('transit-section');
    var form = transitSection.closest('form');
    if (!form) return;
    var primary = document.getElementById('primary_transporter');
    var assistant = document.getElementById('assistant_transporter');
    var errorDiv = document.getElementById('transporter-error');

    function validateTransporters(e) {
        if (primary.value && assistant.value && primary.value === assistant.value) {
            errorDiv.textContent = 'Primary Transporter and Assistant Transporter cannot be the same.';
            errorDiv.style.display = 'block';
            primary.classList.add('is-invalid');
            assistant.classList.add('is-invalid');
            if (e) e.preventDefault();
            return false;
        } else {
            errorDiv.textContent = '';
            errorDiv.style.display = 'none';
            primary.classList.remove('is-invalid');
            assistant.classList.remove('is-invalid');
            return true;
        }
    }

    // Validate on form submit
    form.addEventListener('submit', validateTransporters);
    // Validate on change
    primary.addEventListener('change', validateTransporters);
    assistant.addEventListener('change', validateTransporters);
});
</script>


</html>
