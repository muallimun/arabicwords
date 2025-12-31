<?php
// stats.php - Günlük Sayaçlı
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$host   = 'localhost';
$dbname = 'muallimu_arapcakelime';
$user   = 'muallimu_arapcakelime';
$pass   = 'a01h46206+';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    
    $uuid = isset($_GET['user_uuid']) ? $_GET['user_uuid'] : '';
    if (!$uuid) { echo json_encode(['success' => false]); exit; }

    // 1. Öğrenilenler
    $stmt1 = $pdo->prepare("SELECT COUNT(*) FROM user_progress WHERE user_uuid = ? AND is_learned = 1");
    $stmt1->execute([$uuid]);
    $learned = $stmt1->fetchColumn();

    // 2. Favoriler
    $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM user_progress WHERE user_uuid = ? AND is_favorite = 1");
    $stmt2->execute([$uuid]);
    $favorites = $stmt2->fetchColumn();

    // 3. Toplam Sistem Kelimesi
    $stmt3 = $pdo->query("SELECT COUNT(*) FROM words");
    $total = $stmt3->fetchColumn();

    // 4. BUGÜN ÇALIŞILAN (YENİ ÖZELLİK)
    // Bugün etkileşime girilen (tarihçesi bugün güncellenen) kelime sayısı
    $stmt4 = $pdo->prepare("SELECT COUNT(*) FROM user_progress WHERE user_uuid = ? AND DATE(updated_at) = CURDATE()");
    $stmt4->execute([$uuid]);
    $today_count = $stmt4->fetchColumn();

    // --- RÜTBE HESAPLAMA ---
    $rank = "Yeni Başlayan";
    $rank_icon = "fas fa-seedling";
    $next_rank_target = 10;
    
    if ($learned >= 10 && $learned < 50) {
        $rank = "Çırak"; $rank_icon = "fas fa-user-graduate"; $next_rank_target = 50;
    } elseif ($learned >= 50 && $learned < 200) {
        $rank = "Kalfa"; $rank_icon = "fas fa-hammer"; $next_rank_target = 200;
    } elseif ($learned >= 200 && $learned < 500) {
        $rank = "Usta"; $rank_icon = "fas fa-medal"; $next_rank_target = 500;
    } elseif ($learned >= 500) {
        $rank = "Alim"; $rank_icon = "fas fa-scroll"; $next_rank_target = 1000;
    }

    // --- ROZETLER ---
    $badges = [];
    if ($learned >= 1) $badges[] = ['name' => 'İlk Adım', 'icon' => 'fas fa-shoe-prints', 'color' => '#3498db', 'desc' => 'İlk kelimeyi öğrendin!'];
    if ($favorites >= 5) $badges[] = ['name' => 'Koleksiyoncu', 'icon' => 'fas fa-heart', 'color' => '#e74c3c', 'desc' => '5 Favori kelime biriktirdin.'];
    if ($learned >= 50) $badges[] = ['name' => 'Çalışkan', 'icon' => 'fas fa-brain', 'color' => '#9b59b6', 'desc' => '50 Kelime öğrendin.'];
    if ($learned >= 500) $badges[] = ['name' => 'Efsane', 'icon' => 'fas fa-crown', 'color' => '#f1c40f', 'desc' => '500 Kelime barajını aştın!'];
    if ($today_count >= 20) $badges[] = ['name' => 'Günün Yıldızı', 'icon' => 'fas fa-star', 'color' => '#f39c12', 'desc' => 'Bugün 20 kelime çalıştın!']; // Yeni Rozet

    echo json_encode([
        'success' => true,
        'learned' => $learned,
        'favorites' => $favorites,
        'total_system_words' => $total,
        'today_count' => $today_count, // Yeni Veri
        'rank' => $rank,
        'rank_icon' => $rank_icon,
        'next_target' => $next_rank_target,
        'badges' => $badges
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false]);
}
?>