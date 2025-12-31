<?php
// CORS İzinleri (Eklentinin erişmesi için şart)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json; charset=utf-8');

// Hataları gizle (JSON yapısını bozmasın diye)
error_reporting(0);

$host   = 'localhost';
$dbname = 'muallimu_arapcakelime';
$user   = 'muallimu_arapcakelime';
$pass   = 'a01h46206+';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Parametreleri Al
    $uuid = isset($_GET['user_uuid']) ? $_GET['user_uuid'] : '';
    $source = isset($_GET['source']) ? $_GET['source'] : 'all'; // all, favorites, learned
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; // İstenen soru sayısı

    // Limit güvenlik kontrolü
    if ($limit > 50) $limit = 50;
    if ($limit < 1) $limit = 5;

    // 1. SORULARI SEÇ (Kaynağa Göre)
    $sql = "";
    $params = [];

    if ($source == 'favorites' && $uuid) {
        $sql = "SELECT w.id, w.arabic_word, w.turkish_meaning 
                FROM words w 
                JOIN user_progress up ON w.id = up.word_id 
                WHERE up.user_uuid = ? AND up.is_favorite = 1 
                ORDER BY RAND() LIMIT $limit";
        $params = [$uuid];
    } 
    elseif ($source == 'learned' && $uuid) {
        $sql = "SELECT w.id, w.arabic_word, w.turkish_meaning 
                FROM words w 
                JOIN user_progress up ON w.id = up.word_id 
                WHERE up.user_uuid = ? AND up.is_learned = 1 
                ORDER BY RAND() LIMIT $limit";
        $params = [$uuid];
    } 
    else {
        // Varsayılan: Tüm kelimelerden rastgele seç
        $sql = "SELECT id, arabic_word, turkish_meaning FROM words ORDER BY RAND() LIMIT $limit";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $questions_raw = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Eğer hiç soru yoksa hata dön
    if (!$questions_raw || count($questions_raw) == 0) {
        echo json_encode(['success' => false, 'error' => 'empty_list', 'message' => 'Bu kategoride soru bulunamadı.']);
        exit;
    }

    // 2. HER SORU İÇİN ŞIKLARI HAZIRLA
    $final_quiz = [];

    foreach ($questions_raw as $q) {
        // Bu soru hariç rastgele 3 yanlış cevap bul
        $stmt2 = $pdo->prepare("SELECT turkish_meaning FROM words WHERE id != ? ORDER BY RAND() LIMIT 3");
        $stmt2->execute([$q['id']]);
        $wrong_answers = $stmt2->fetchAll(PDO::FETCH_COLUMN);

        // Şıkları birleştir
        $options = [];
        // Doğru cevap
        $options[] = ['text' => $q['turkish_meaning'], 'is_correct' => true];
        
        // Yanlış cevaplar
        foreach ($wrong_answers as $wrong) {
            if(!empty($wrong)) {
                $options[] = ['text' => $wrong, 'is_correct' => false];
            }
        }
        
        // Şıkları karıştır
        shuffle($options);

        // Pakete ekle
        $final_quiz[] = [
            'id' => $q['id'],
            'question' => $q['arabic_word'],
            'options' => $options
        ];
    }

    // Başarılı sonucu döndür
    echo json_encode([
        'success' => true,
        'quiz_data' => $final_quiz
    ]);

} catch (PDOException $e) {
    // Veritabanı hatası durumunda
    echo json_encode(['success' => false, 'error' => 'db_error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
}
?>