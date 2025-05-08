<?php
require_once __DIR__ . "/../model.php";

class StatisticsModel extends Model {

    public function getWorkloadDistribution(int $departmentId): array {
                        $query = "SELECT
                                workload_status AS status,
                                COUNT(*) AS total
                            FROM (
                                SELECT 
                                    p.id_professor,
                                    p.min_hours,
                                    p.max_hours,
                                    COALESCE(SUM(
                                        COALESCE(m.volume_cours, 0) + 
                                        COALESCE(m.volume_td, 0) + 
                                        COALESCE(m.volume_tp, 0) + 
                                        COALESCE(m.volume_autre, 0)
                                    ), 0) AS total_assigned,
                                    CASE 
                                        WHEN COALESCE(SUM(
                                            COALESCE(m.volume_cours, 0) + 
                                            COALESCE(m.volume_td, 0) + 
                                            COALESCE(m.volume_tp, 0) + 
                                            COALESCE(m.volume_autre, 0)
                                        ), 0) < p.min_hours THEN 'Insuffisante'
                                        WHEN COALESCE(SUM(
                                            COALESCE(m.volume_cours, 0) + 
                                            COALESCE(m.volume_td, 0) + 
                                            COALESCE(m.volume_tp, 0) + 
                                            COALESCE(m.volume_autre, 0)
                                        ), 0) > p.max_hours THEN 'Dépassée'
                                        ELSE 'Correcte'
                                    END AS workload_status
                                FROM professor p
                                JOIN user u ON u.id_user = p.id_professor
                                LEFT JOIN affectation_professor ap ON ap.to_professor = p.id_professor
                                LEFT JOIN module m ON m.id_module = ap.id_module
                                WHERE p.id_deparetement = ?
                                GROUP BY p.id_professor, p.min_hours, p.max_hours
                            ) AS derived
                            GROUP BY workload_status;";

        return $this->db->query($query, [$departmentId]) ? $this->db->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    public function getModuleChoicesStats(int $departmentId): array {
        $query = "SELECT status, COUNT(*) as count
                  FROM choix_module cm
                  JOIN module m ON m.id_module = cm.id_module
                  JOIN filiere f ON f.id_filiere = m.id_filiere
                  WHERE f.id_deparetement = ?
                  GROUP BY status";

        return $this->db->query($query, [$departmentId]) ? $this->db->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    public function getModuleValidationStats(int $departmentId): array {
        $query = "SELECT YEAR(cm.date_reponce) as year, COUNT(*) as validated
                  FROM choix_module cm
                  JOIN module m ON cm.id_module = m.id_module
                  JOIN filiere f ON m.id_filiere = f.id_filiere
                  WHERE f.id_deparetement = ? AND cm.status = 'validated'
                  GROUP BY YEAR(cm.date_reponce)
                  ORDER BY year DESC";

        return $this->db->query($query, [$departmentId]) ? $this->db->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    public function getPendingValidationsCount(int $departmentId): int {
        $query = "SELECT COUNT(*) 
                  FROM choix_module cm
                  JOIN module m ON cm.id_module = m.id_module
                  JOIN filiere f ON m.id_filiere = f.id_filiere
                  WHERE cm.status = 'in progress' AND f.id_deparetement = ?";
    
        if ($this->db->query($query, [$departmentId])) {
            return (int) $this->db->fetchColumn();
        } else {
            return 0;
        }
    }
    
    public function getRecentModuleActivities(int $departmentId, int $limit = 10): array {
        $query = "SELECT 
                    CONCAT(u.firstName, ' ', u.lastName) AS user,
                    m.title AS module,
                    cm.status,
                    cm.date_reponce AS timestamp
                  FROM choix_module cm
                  JOIN module m ON m.id_module = cm.id_module
                  JOIN filiere f ON f.id_filiere = m.id_filiere
                  JOIN user u ON u.id_user = cm.by_professor
                  WHERE f.id_deparetement = ?
                  ORDER BY cm.date_reponce DESC
                  LIMIT ?";
        
        return $this->db->query($query, [$departmentId, $limit]) 
            ? $this->db->fetchAll(PDO::FETCH_ASSOC) 
            : [];
    }
    
    public function getModuleValidationStatsByMonth(int $departmentId, int $year): array {
        $query = "SELECT MONTH(cm.date_reponce) as month, COUNT(*) as validated
                  FROM choix_module cm
                  JOIN module m ON cm.id_module = m.id_module
                  JOIN filiere f ON m.id_filiere = f.id_filiere
                  WHERE f.id_deparetement = ? AND cm.status = 'validated' AND YEAR(cm.date_reponce) = ?
                  GROUP BY MONTH(cm.date_reponce)
                  ORDER BY month";
                  
        return $this->db->query($query, [$departmentId, $year]) 
            ? $this->db->fetchAll(PDO::FETCH_ASSOC) 
            : [];
    }
    
    public function getVacantModulesCount(int $departmentId): int {
        $query = "SELECT COUNT(*) AS vacant_count
                    FROM module m
                    JOIN filiere f ON m.id_filiere = f.id_filiere
                    WHERE f.id_deparetement = ?
                    AND m.id_module NOT IN (
                        SELECT id_module FROM affectation_professor
                        UNION
                        SELECT id_module FROM choix_module
                    )
                    ";
    
        if ($this->db->query($query, [$departmentId])) {
            $result = $this->db->fetch(PDO::FETCH_ASSOC);
            return (int)($result['vacant_count'] ?? 0);
        } else {
            return 0;
        }
    }

    public function getHistoricalData(int $departmentId): array {
        $query = "SELECT 
                    YEAR(ap.annee) AS annee,
                    CONCAT(u.firstName, ' ', u.lastName) AS professeur,
                    u.email,
                    SUM(m.volume_cours + m.volume_td + m.volume_tp + m.volume_autre) AS total_heures,
                    GROUP_CONCAT(DISTINCT m.title SEPARATOR ', ') AS modules,
                    GROUP_CONCAT(DISTINCT f.title SEPARATOR ', ') AS filieres,
                    SUM(m.volume_cours) AS volume_cour,
                    SUM(m.volume_td) AS volume_td,
                    SUM(m.volume_tp) AS volume_tp,
                    SUM(m.volume_autre) AS volume_autre
                FROM affectation_professor ap
                JOIN module m ON m.id_module = ap.id_module
                JOIN filiere f ON f.id_filiere = m.id_filiere
                JOIN user u ON u.id_user = ap.to_professor
                JOIN professor p ON p.id_professor = u.id_user
                WHERE f.id_deparetement = ?
                GROUP BY annee, ap.to_professor
                ORDER BY annee DESC, professeur ASC";
    
        if ($this->db->query($query, [$departmentId])) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }
    
    
    
}
