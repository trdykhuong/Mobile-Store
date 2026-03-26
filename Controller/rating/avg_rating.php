<?php
    // Import file Database
    include_once __DIR__ . "/../../Model/db_connect.php";

    // Tạo đối tượng Database và kết nối
    $db = new Database();
    $db->connect();

    function get_AvgRating($db, $idSP) {
        $query = "SELECT COUNT(*) as total, AVG(rating) as avg_rating FROM rating WHERE idSP = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $idSP);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
    
        $totalReviews = $result['total'] ?? 0;
        $avgRating = round($result['avg_rating'] ?? 0, 1);
    
        // Lấy số lượng từng mức sao
        $ratingsCount = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        $query = "SELECT rating, COUNT(*) as count FROM rating WHERE idSP = ? GROUP BY rating";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $idSP);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $ratingsCount[$row['rating']] = $row['count'];
        }
    
        return [
            'avgRating' => $avgRating,
            'totalReviews' => $totalReviews,
            'ratingsCount' => $ratingsCount
        ];
    }
    ?>
