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

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối cơ sở dữ liệu thất bại: " . $conn->connect_error);
}

// Đọc dữ liệu từ Vietnamese.json
$inputFile = 'Vietnamese.json';
$data = json_decode(file_get_contents($inputFile), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die("Lỗi khi đọc JSON từ file Vietnamese.json");
}

// Tạo danh sách các label từ Vietnamese.json
$existingLabels = array_column($data['translations'], 'label');

// Lấy các label và translation cho ngôn ngữ tiếng Anh (lang_id = 1)
$sqlEnglish = "SELECT label, translation FROM language_translations WHERE lang_id = 1";
$resultEnglish = $conn->query($sqlEnglish);

$noTranslationData = [
    'language' => $data['language'],
    'translations' => []
];

// Kiểm tra các label tiếng Anh chưa có trong tiếng Việt hoặc Vietnamese.json
if ($resultEnglish->num_rows > 0) {
    while ($row = $resultEnglish->fetch_assoc()) {
        $label = $row['label'];
        $translation = $row['translation'];

        // Kiểm tra điều kiện 1: Nếu label không tồn tại trong Vietnamese.json
        $condition1 = !in_array($label, $existingLabels);

        // Kiểm tra điều kiện 2: Nếu label không tồn tại trong language_translations cho tiếng Việt (lang_id = 2)
        $sqlVietnamese = "SELECT COUNT(*) AS count FROM language_translations WHERE lang_id = 2 AND label = '$label'";
        $resultVietnamese = $conn->query($sqlVietnamese);
        $rowVietnamese = $resultVietnamese->fetch_assoc();
        $condition2 = ($rowVietnamese['count'] == 0);

        // Nếu một trong hai điều kiện thỏa mãn, thêm label và translation vào danh sách noTranslationData
        if ($condition1 || $condition2) {
            $noTranslationData['translations'][] = [
                'label' => $label,
                'translation' => $translation
            ];
        }
    }
} else {
    echo "Không tìm thấy bản ghi nào trong bảng language_translations cho tiếng Anh.";
}

$conn->close();

// Ghi dữ liệu vào file Vietnamese_NoTran.json
$outputFile = 'Vietnamese_NoTran.json';
file_put_contents($outputFile, json_encode($noTranslationData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

echo "File Vietnamese_NoTran.json đã được tạo thành công.";
?>