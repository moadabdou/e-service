<?php

class UnitModel {

private PDO $db;

public function __construct(Database $database) {
$this->db = $database->getPdoInstance();
}

/**
* Retrieves details of a specific unit by its ID.
*
* @param int $unitId The ID of the unit.
* @return array|null An associative array containing the unit details, or null if not found.
*/
public function getUnitById(int $unitId): ?array {
$stmt = $this->db->prepare("SELECT * FROM units WHERE id_unit = :id");
$stmt->bindParam(':id', $unitId, PDO::PARAM_INT);
$stmt->execute();
return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
* Retrieves details of multiple units by their IDs.
*
* @param array $unitIds An array of unit IDs.
* @return array An array of associative arrays containing the unit details.
*/
public function getUnitsByIds(array $unitIds): array {
if (empty($unitIds)) {
return [];
}
$placeholders = implode(',', array_fill(0, count($unitIds), '?'));
$stmt = $this->db->prepare("SELECT id_unit, title, hourly_volume, specialty FROM units WHERE id_unit IN ($placeholders)");
$stmt->execute($unitIds);
return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
* Retrieves all available teaching units for a specific academic year.
*
* @param string $academicYear The academic year (e.g., "2023/2024").
* @return array An array of associative arrays containing the unit details.
*/
public function getAvailableUnitsForYear(string $academicYear): array {
// Adjust the query based on how you store academic year information
$stmt = $this->db->prepare("SELECT id_unit, title, hourly_volume, specialty FROM units WHERE academic_year = :academic_year");
$stmt->bindParam(':academic_year', $academicYear, PDO::PARAM_STR);
$stmt->execute();
return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
?>
