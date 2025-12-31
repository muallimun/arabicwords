<?php
// Hata Bildirim API'si
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json; charset=utf-8');

// Hataları göster (Geliştirme aşamasında iyi olur)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host   = 'localhost';
$dbname = 'muallimu_arapcakelime';
$user   = 'muallimu_arapcakelime';
$pass   = 'a01h46206+';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'Veri alınamadı.']);
        exit;
    }

    if (empty($data['word_id']) || empty($data['message'])) {
        echo json_encode(['success' => false, 'message' => 'Eksik veri.']);
        exit;
    }

    // GÜNCELLENEN KISIM: 'source' eklendi
    $stmt = $pdo->prepare("INSERT INTO error_reports (word_id, user_uuid, message, source) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $data['word_id'],
        $data['user_uuid'] ?? 'guest',
        $data['message'],
        $data['source'] ?? 'extension' // Eğer bilgi gelmezse varsayılan 'extension' olsun
    ]);

    echo json_encode(['success' => true, 'message' => 'Rapor kaydedildi.']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
}
?>