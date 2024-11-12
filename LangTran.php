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
$conn->set_charset("utf8");

// Lấy các label và translation cho ngôn ngữ tiếng Việt (lang_id = 2)
$sqlVietnamese = "SELECT label, translation FROM language_translations WHERE lang_id=2 and label in (Select label from language_translations where lang_id=1);";
$resultVietnamese = $conn->query($sqlVietnamese);

$translations = [];
if ($resultVietnamese->num_rows > 0) {
    while ($row = $resultVietnamese->fetch_assoc()) {
        $translations[] = [
            'label' => $row['label'],
            'translation' => $row['translation'],
        ];
    }
} else {
    echo "Không tìm thấy bản ghi nào trong bảng language_translations cho tiếng Việt.";
}

$languageData = [
    "language" => [
        "name" => "Tiếng Việt",
        "short_form" => "vi",
        "language_code" => "vi-VN",
        "text_direction" => "ltr",
        "text_editor_lang" => "vi"
    ],
    "translations" => $translations
];

// Ghi dữ liệu vào file Vietnamese.json
$outputFile = 'Vietnamese.json';
$jsonData = json_encode($languageData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

if (file_put_contents($outputFile, $jsonData) === false) {
    die("Lỗi khi ghi dữ liệu vào file $outputFile.");
}

echo "File Vietnamese.json đã được tạo thành công.";
$conn->close();

?>