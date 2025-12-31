<?php
// Hata raporlamayı aç (Geliştirme aşamasında hayat kurtarır)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// --- CORS İZİNLERİ (Kritik Kısım) ---
// Tarayıcının her yerden erişmesine izin ver
header("Access-Control-Allow-Origin: *");
// Hangi metodlara izin verileceğini belirt
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
// Hangi başlıklara (Header) izin verileceğini belirt
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Eğer tarayıcı "Ön Kontrol" (OPTIONS) isteği atıyorsa, hemen "Tamam" de ve işlemi bitir.
// Veritabanına bağlanmaya çalışma.
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

header('Content-Type: application/json; charset=utf-8');

// VERİTABANI BAĞLANTISI
$host   = 'localhost';
$dbname = 'muallimu_arapcakelime';
$user   = 'muallimu_arapcakelime';
$pass   = 'a01h46206+';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // POST ile gelen JSON verisini al
    $json = file_get_contents('php://input');
    $input = json_decode($json, true);

    // Veri kontrolü
    if (!$input || !isset($input['user_uuid']) || !isset($input['word_id'])) {
        // Eğer boş istek gelirse hata döndür
        echo json_encode(['success' => false, 'error' => 'Eksik veri']);
        exit;
    }

    $uuid = $input['user_uuid'];
    $word_id = $input['word_id'];
    $action = isset($input['action']) ? $input['action'] : '';

    // Önce kayıt var mı bak
    $stmt = $pdo->prepare("SELECT * FROM user_progress WHERE user_uuid = ? AND word_id = ?");
    $stmt->execute([$uuid, $word_id]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    $is_favorite = 0;
    $is_learned = 0;

    if ($record) {
        $is_favorite = $record['is_favorite'];
        $is_learned = $record['is_learned'];
    } else {
        // Kayıt yoksa oluştur
        $ins = $pdo->prepare("INSERT INTO user_progress (user_uuid, word_id) VALUES (?, ?)");
        $ins->execute([$uuid, $word_id]);
    }

    // İşlem mantığı
    if ($action == 'toggle_fav') {
        $is_favorite = ($is_favorite == 1) ? 0 : 1;
    } elseif ($action == 'toggle_learn') {
        $is_learned = ($is_learned == 1) ? 0 : 1;
    }

    // Güncelle
    $upd = $pdo->prepare("UPDATE user_progress SET is_favorite = ?, is_learned = ? WHERE user_uuid = ? AND word_id = ?");
    $upd->execute([$is_favorite, $is_learned, $uuid, $word_id]);

    echo json_encode([
        'success' => true, 
        'is_favorite' => (bool)$is_favorite, 
        'is_learned' => (bool)$is_learned
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>