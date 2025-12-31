<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$host   = 'localhost';
$dbname = 'muallimu_arapcakelime';
$user   = 'muallimu_arapcakelime';
$pass   = 'a01h46206+';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);

    $uuid = $_GET['user_uuid'];
    $type = $_GET['type']; // 'favorites' veya 'learned'

    if (!$uuid || !$type) {
        echo json_encode([]);
        exit;
    }

    $condition = ($type == 'favorites') ? 'is_favorite = 1' : 'is_learned = 1';

    // Kelimeleri çek
    $sql = "SELECT w.id, w.arabic_word, w.turkish_meaning 
            FROM words w 
            JOIN user_progress up ON w.id = up.word_id 
            WHERE up.user_uuid = ? AND up.$condition 
            ORDER BY up.updated_at DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$uuid]);
    $words = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($words);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>