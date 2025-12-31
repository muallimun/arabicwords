<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$host   = 'localhost';
$dbname = 'muallimu_arapcakelime';
$user   = 'muallimu_arapcakelime';
$pass   = 'a01h46206+';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Kullanıcı kimliğini (UUID) al
    $uuid = isset($_GET['user_uuid']) ? $_GET['user_uuid'] : '';

    // Kelimeyi seçerken Kullanıcı Tablosuyla (LEFT JOIN) birleştir
    // Böylece "Bu kullanıcı bu kelimeyi favorilemiş mi?" bilgisini de çekeriz.
    $sql = "SELECT w.*, 
            COALESCE(up.is_favorite, 0) as is_favorite, 
            COALESCE(up.is_learned, 0) as is_learned
            FROM words w
            LEFT JOIN user_progress up ON w.id = up.word_id AND up.user_uuid = ?
            ORDER BY RAND() LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$uuid]);
    $word = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($word) {
        echo json_encode($word, JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['error' => 'Kelime bulunamadı.']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>