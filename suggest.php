<?php
// Kelime Öneri API'si
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json; charset=utf-8');

$host   = 'localhost';
$dbname = 'muallimu_arapcakelime';
$user   = 'muallimu_arapcakelime';
$pass   = 'a01h46206+';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    if (!$data || empty($data['arabic_word']) || empty($data['turkish_meaning'])) {
        echo json_encode(['success' => false, 'message' => 'Eksik veri.']);
        exit;
    }

    // GÜNCELLENEN KISIM: 'source' eklendi
    $stmt = $pdo->prepare("INSERT INTO word_suggestions (user_uuid, arabic_word, turkish_meaning, example_sentence, sentence_translation, source) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['user_uuid'] ?? 'guest',
        $data['arabic_word'],
        $data['turkish_meaning'],
        $data['example_sentence'],
        $data['sentence_translation'],
        $data['source'] ?? 'extension' // Varsayılan 'extension'
    ]);

    echo json_encode(['success' => true, 'message' => 'Öneri alındı.']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
}
?>