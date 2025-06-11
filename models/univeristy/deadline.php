<?php
require_once __DIR__ . "/../model.php";

class DeadlineModel extends Model
{
    public function getAllDeadlines(): array {
        $this->db->query("SELECT * FROM feature_deadlines ORDER BY start_date DESC");
        return $this->db->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createDeadline(string $feature, string $startDate, string $endDate): bool {
        return $this->db->query(
            "INSERT INTO feature_deadlines (feature, start_date, end_date, status) VALUES (?, ?, ?, 'open')",
            [$feature, $startDate, $endDate]
        );
    }

public function updateDeadlineStatus(int $id, string $status): bool {
    $sql = "UPDATE feature_deadlines SET status = ? WHERE id = ?";
    $params = [$status, $id];

    return $this->db->query($sql, $params);
}


    public function deleteDeadline(int $id): bool {
        return $this->db->query("DELETE FROM feature_deadlines WHERE id = ?", [$id]);
    }

    public function closeDeadline(int $id): bool {
        return $this->updateDeadlineStatus($id, 'closed');
    }

    public function isFeatureOpen(string $feature): bool {
        $now = date('Y-m-d H:i:s');
        $this->db->query(
            "SELECT COUNT(*) FROM feature_deadlines
             WHERE feature = ? AND status = 'open' AND start_date <= ? AND end_date >= ?",
            [$feature, $now, $now]
        );
        return $this->db->fetchColumn() > 0;
    }

    public function getRemainingTime(string $feature): ?array {
        $now = new DateTime();
        $this->db->query(
            "SELECT end_date FROM feature_deadlines 
             WHERE feature = ? AND status = 'open' AND end_date >= NOW()
             ORDER BY end_date ASC LIMIT 1",
            [$feature]
        );

        $endDateStr = $this->db->fetchColumn();
        if (!$endDateStr) return null;

        $endDate = new DateTime($endDateStr);
        if ($endDate < $now) return null;

        $interval = $now->diff($endDate);

        return [
            'days' => $interval->d,
            'hours' => $interval->h,
            'minutes' => $interval->i
        ];
    }
    
    public function getRemainingMinutesForFeature(string $feature): ?int
    {
        $now = new DateTime();
        
        $this->db->query(
            "SELECT end_date FROM feature_deadlines 
            WHERE feature = ? AND status = 'open' AND end_date >= NOW()
            ORDER BY end_date ASC LIMIT 1",
            [$feature]
        );
        
        $endDateStr = $this->db->fetchColumn();
        if (!$endDateStr) {
            return null; 
        }
        
        $endDate = new DateTime($endDateStr);
        if ($endDate < $now) {
            return null; 
        }
        
        $interval = $now->diff($endDate);
        $totalMinutes = $interval->days * 24 * 60 + $interval->h * 60 + $interval->i;
        return $totalMinutes;
    }


    public function getDeadlineById(int $id): ?array {
        if ($this->db->query("SELECT * FROM feature_deadlines WHERE id = ?", [$id])) {
            return $this->db->fetch();
        }
        return null;
    }
    
    public function updateDeadline(int $id, string $feature, string $startDate, string $endDate): bool {
        return $this->db->query(
            "UPDATE feature_deadlines SET feature = ?, start_date = ?, end_date = ? WHERE id = ?",
            [$feature, $startDate, $endDate, $id]
        );
    }

    public function create(string $title, string $content, string $createTime, int $chefId): bool {
        $sql = "INSERT INTO announces (title, content, create_time, id_admin) VALUES (?, ?, ?, ?)";
        $params = [$title, $content, $createTime, $chefId];
        $res = $this->db->query($sql,$params);
        return $res;

    }
    public function getDeadlinesExpiredWithinLastDay(): array
        {
            $this->db->query(
                "SELECT feature, end_date FROM feature_deadlines
                WHERE end_date < NOW()
                AND end_date >= DATE_SUB(NOW(), INTERVAL 1 DAY)"
            );
            
            return $this->db->fetchAll();  // Returns an array of ['feature' => ..., 'end_date' => ...]
        }


    
}
// Format helper
function formatRemainingTime(array $remaining): string {
    $parts = [];
    if ($remaining['days'] > 0) {
        $parts[] = $remaining['days'] . ' jour' . ($remaining['days'] > 1 ? 's' : '');
    }
    if ($remaining['hours'] > 0) {
        $parts[] = $remaining['hours'] . ' heure' . ($remaining['hours'] > 1 ? 's' : '');
    }
    // Always show minutes, even if 0
    $parts[] = $remaining['minutes'] . ' minute' . ($remaining['minutes'] > 1 ? 's' : '');

    return implode(', ', $parts);
}