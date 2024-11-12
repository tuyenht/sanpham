<?php
$configPath = __DIR__ . '/app/Config/Database.php';
$fileContent = file_get_contents($configPath);
preg_match("/'hostname'\s*=>\s*'([^']+)'/", $fileContent, $hostnameMatch);
preg_match("/'username'\s*=>\s*'([^']+)'/", $fileContent, $usernameMatch);
preg_match("/'password'\s*=>\s*'([^']+)'/", $fileContent, $passwordMatch);
preg_match("/'database'\s*=>\s*'([^']+)'/", $fileContent, $databaseMatch);
$servername = $hostnameMatch[1] ?? 'localhost';
$username = $usernameMatch[1] ?? '';
$password = $passwordMatch[1] ?? '';
$dbname = $databaseMatch[1] ?? '';

$mysqli = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
// Lấy tên file từ query string
$jsonFile = isset($_GET['file']) ? $_GET['file'] : 'Vietnamese.json';

// Đọc file JSON
$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);

// Thiết lập kết nối sử dụng UTF-8 để hỗ trợ tiếng Việt
$mysqli->set_charset("utf8");

// Lấy thông tin "short_form" từ JSON
$shortForm = $data['language']['short_form'];

// Lấy id của ngôn ngữ từ bảng "languages"
$stmt = $mysqli->prepare("SELECT id FROM languages WHERE short_form = ?");
$stmt->bind_param("s", $shortForm);
$stmt->execute();
$result = $stmt->get_result();
$langId = null;

if ($row = $result->fetch_assoc()) {
    $langId = $row['id'];
} else {
    die("Không tìm thấy ngôn ngữ với short_form = $shortForm.");
}

$stmt->close();

// Lặp qua các bản dịch trong JSON
foreach ($data['translations'] as $translation) {
    $label = $translation['label'];
    $translationValue = $translation['translation'];

    // Kiểm tra xem label có tồn tại trong bảng "language_translations" hay không
    $stmt = $mysqli->prepare("SELECT id FROM language_translations WHERE lang_id = ? AND label = ?");
    $stmt->bind_param("is", $langId, $label);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Nếu đã có, thực hiện cập nhật
        $stmt = $mysqli->prepare("UPDATE language_translations SET translation = ? WHERE lang_id = ? AND label = ?");
        $stmt->bind_param("sis", $translationValue, $langId, $label);
        $stmt->execute();
    } else {
        // Nếu chưa có, thực hiện thêm mới
        $stmt = $mysqli->prepare("INSERT INTO language_translations (lang_id, label, translation) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $langId, $label, $translationValue);
        $stmt->execute();
    }

    $stmt->close();
}

// Đóng kết nối
$mysqli->close();

echo "Cập nhật hoặc thêm mới các bản dịch thành công.";
?>
