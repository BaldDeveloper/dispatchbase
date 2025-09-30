<?php
// transit-edit.php
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
<!-- transit-edit.php: Pure view, expects $originLocations, $destinationLocations, $coroners, $pouchTypes, $originLocation, $destinationLocation, $coronerName, $transitPermitNumber, $tagNumber, $pouchType -->
<div id="transit-section">
    <table style="width:100%;">
        <tr>
            <td style="padding:10px;">
                <label for="origin_location">Origin Location</label><br>
                <select id="origin_location" name="origin_location" class="form-control" style="width:95%;">
                    <?php foreach ($originLocations as $origin): ?>
                        <option value="<?= htmlspecialchars($origin['name']) ?>" <?= (isset($originLocation) && $originLocation === $origin['name']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($origin['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td style="padding:10px;">
                <label for="destination_location">Destination Location</label><br>
                <select id="destination_location" name="destination_location" class="form-control" style="width:95%;">
                    <?php foreach ($destinationLocations as $destination): ?>
                        <option value="<?= htmlspecialchars($destination['name']) ?>" <?= (isset($destinationLocation) && $destinationLocation === $destination['name']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($destination['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td style="padding:10px;">
                <label for="coroner">Coroner</label><br>
                <select id="coroner" name="coroner" class="form-control" style="width:95%;">
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
                <label for="tag_number">Tag Number</label><br>
                <input type="text" id="tag_number" name="tag_number" class="form-control" style="width:95%;" value="<?= htmlspecialchars($tagNumber ?? '') ?>">
            </td>
            <td style="padding:10px;">
                <label for="pouch_type">Pouch Type</label><br>
                <select id="pouch_type" name="pouch_type" class="form-control" style="width:95%;">
                    <?php foreach ($pouchTypes as $pouch): ?>
                        <?php $type = $pouch['pouch_type'] ?? $pouch['type'] ?? $pouch; ?>
                        <option value="<?= htmlspecialchars($type) ?>" <?= (isset($pouchType) && $pouchType === $type) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($type) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <!-- Next row will be added as per user instruction -->
    </table>
</div>


</html>
