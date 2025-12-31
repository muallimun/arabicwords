<div align="center">

  <img src="icon.png" alt="Muallim Logo" width="120" height="120" />

  # 🕌 Muallim - Akıllı Arapça Kelime Asistanı

  **YDS, YÖKDİL ve İlahiyat Hazırlık Sürecinde En Büyük Yardımcınız**

  [![Chrome Extension](https://img.shields.io/badge/Chrome-Extension-4285F4?style=for-the-badge&logo=google-chrome&logoColor=white)](https://chrome.google.com/webstore)
  [![PWA Ready](https://img.shields.io/badge/PWA-Mobile%20Ready-5A0FC8?style=for-the-badge&logo=pwa&logoColor=white)](https://arapca.muallimun.net/api/arabicwords.html)
  [![PHP](https://img.shields.io/badge/Backend-PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
  [![MySQL](https://img.shields.io/badge/Database-MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
  [![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](./LICENSE)

  <p class="description">
    Muallim, modern web teknolojileri kullanılarak geliştirilmiş, hibrit yapıda çalışan bir kelime öğrenme platformudur. 
    İster <strong>Chrome Eklentisi</strong> olarak tarayıcınızda, ister <strong>Mobil Uygulama</strong> olarak cebinizde kullanın; öğrenme süreciniz daima senkronize kalır.
  </p>

  <h3>🚀 <a href="https://arapca.muallimun.net/api/arabicwords.html">Tanıtım ve İndirme Sayfasına Git</a> 🚀</h3>

</div>

---

## ✨ Özellikler

Bu proje, sadece bir kelime kartı uygulaması değil, tam teşekküllü bir eğitim ekosistemidir.

### 🎯 Kullanıcı Deneyimi (Frontend)
* 🃏 **Akıllı Flashcard Sistemi:** Kelimelerin Arapçası, Türkçesi ve örnek cümleleri ile interaktif kartlar.
* 🧠 **Quiz Modu:** Öğrendiklerinizi test etmek için çoktan seçmeli veya kart çevirmeli sınavlar.
* 🔊 **Sesli Telaffuz:** Kelimelerin doğru okunuşunu anında dinleyin.
* 📊 **Detaylı İstatistikler:** Öğrenilen kelime sayısı, favoriler ve seviye durumu takibi.
* 🔄 **Çoklu Platform:** Verileriniz Chrome Eklentisi ve Mobil Uygulama arasında anlık senkronize olur.
* 🌙 **Koyu Mod (Dark Mode):** Gece çalışmaları için göz yormayan tema.
* 🔍 **Gelişmiş Arama:** Kelime listenizde anlık filtreleme yapın.
* 📲 **Kolay Paylaşım:** Kelimeleri veya uygulamayı tek tıkla WhatsApp ve diğer platformlarda paylaşın.

### 🛠️ Yönetim Paneli (Backend)
* 📈 **Dashboard:** Kullanıcı aktivitelerini, platform dağılımını (Mobil/PC) ve içerik durumunu grafiklerle izleyin.
* 📝 **İçerik Yönetimi:** Kelime ekleme, düzenleme, silme ve Excel/CSV ile toplu yükleme/dışa aktarma.
* 🛡️ **Hata ve Öneri Takibi:** Kullanıcılardan gelen düzeltme bildirimlerini ve kelime önerilerini yönetin.
* 🔒 **Güvenlik:** Kullanıcı ve kaynak (Source) doğrulama sistemleri.

---

## 📸 Ekran Görüntüleri

| Chrome Eklentisi | Mobil Uygulama (PWA) | Yönetim Paneli |
|:---:|:---:|:---:|
| <img src="docs/extension_screenshot.png" width="250" alt="Eklenti Görünümü"> | <img src="docs/mobile_screenshot.png" width="250" alt="Mobil Görünümü"> | <img src="docs/admin_screenshot.png" width="250" alt="Admin Paneli"> |

*(Ekran görüntüleri `docs` klasörüne eklenecektir)*

---

## 🏗️ Kurulum ve Yapılandırma

Projeyi kendi sunucunuza kurmak ve geliştirmek için aşağıdaki adımları izleyin.

### 1. Sunucu ve Veritabanı (Backend)
1.  Bu repoyu indirin ve sunucunuza yükleyin.
2.  `database.sql` dosyasını MySQL veritabanınıza içe aktarın (Import).
3.  `api/config.php` ve `admin/config.php` dosyalarındaki veritabanı bilgilerini güncelleyin:
    ```php
    $host = 'localhost';
    $dbname = 'veritabani_adi';
    $user = 'kullanici_adi';
    $pass = 'sifre';
    ```

### 2. Chrome Eklentisi (Extension)
1.  Chrome tarayıcısında `chrome://extensions/` adresine gidin.
2.  Sağ üstten **"Geliştirici Modu"**nu açın.
3.  **"Paketlenmemiş öğe yükle"** butonuna tıklayın ve projenin ana klasörünü seçin.

### 3. Mobil Uygulama (PWA)
1.  Sunucunuzdaki `mobile/` klasörüne tarayıcıdan erişin (Örn: `siteadi.com/mobile`).
2.  Android/iOS tarayıcı menüsünden **"Ana Ekrana Ekle"** seçeneğine tıklayın.
3.  Uygulama telefonunuza yerel bir uygulama gibi yüklenecektir.

---

## 📂 Proje Yapısı
muallim-arapca/ ├── admin/ # Yönetim Paneli (PHP) │ ├── index.php # Dashboard & Grafikler │ ├── words.php # Kelime Yönetimi (CRUD & CSV) │ └── ... ├── api/ # REST API Servisleri (Mobil & Eklenti için) │ ├── get_word.php # Kelime getirme servisi │ ├── report.php # Hata bildirim servisi │ └── ... ├── mobile/ # PWA Mobil Uygulama Dosyaları │ ├── index.html # Mobil Arayüz │ └── popup.js # Mobil Logic (Source: Mobile) ├── popup.html # Chrome Eklenti Arayüzü ├── popup.js # Eklenti Logic (Source: Extension) ├── manifest.json # Chrome Manifest V3 Dosyası └── README.md # Proje Dokümantasyonu
---

## 🤝 Katkıda Bulunma

Projeye katkıda bulunmak isterseniz çok mutlu oluruz!
1.  Bu repoyu **Fork** edin.
2.  Yeni bir özellik dalı (branch) oluşturun (`git checkout -b yeni-ozellik`).
3.  Değişikliklerinizi yapın ve **Commit** edin (`git commit -m 'Yeni özellik eklendi'`).
4.  Dalınızı **Push** edin (`git push origin yeni-ozellik`).
5.  Bir **Pull Request** oluşturun.

---

## 📄 Lisans

Bu proje **MIT Lisansı** ile lisanslanmıştır. Açık kaynak kodludur, eğitim amaçlı özgürce kullanılabilir ve dağıtılabilir.

---

<div align="center">
  <strong>Muallimun Ekibi Tarafından ❤️ ile Geliştirildi</strong>
  <br>
  <a href="https://arapca.muallimun.net/api/arabicwords.html">Web Sitemizi Ziyaret Edin</a>
</div>