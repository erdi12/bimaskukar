# Implementation Plan - Add Kecamatan Filter to Marbot Export

## Goal
Enable users to filter the Marbot Excel export by "Kecamatan" (District) in addition to the existing Status and Date Range filters.

## Changes

### 1. `app/Exports/MarbotExport.php`
- Updated the `__construct` method to accept a new `$kecamatanId` parameter.
- Added a protected property `$kecamatanId`.
- Updated the `query` method to apply a `where` clause on `kecamatan_id` if `$kecamatanId` is provided.

### 2. `app/Http/Controllers/MarbotController.php`
- Updated the `export` method to:
    - Retrieve `kecamatan_id` from the request.
    - Pass `kecamatan_id` to the `MarbotExport` constructor when creating the export instance.

### 3. `resources/views/backend/marbot/index.blade.php`
- Modified the `#exportModal` form.
- Added a `<select>` dropdown for "Kecamatan" populated with the `$kecamatans` data passed from the controller.
- This allows the user to select a specific kecamatan or "Semua Kecamatan" (All Districts) before downloading.

## Verification
- Open the "Export Excel" modal on the Marbot index page.
- Verify that a "Kecamatan" dropdown appears.
- Select a specific Kecamatan and click "Download".
- Verify that the downloaded Excel file only contains records for the selected Kecamatan.
- Select "Semua Kecamatan" and verified that all records are included (subject to other filters).
