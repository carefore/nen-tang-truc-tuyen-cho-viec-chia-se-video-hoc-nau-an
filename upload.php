<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "ten_csdl";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối không thành công: " . $conn->connect_error);
}

// Xử lý upload video
$targetDir = "uploads/";
$targetFile = $targetDir . basename($_FILES["video"]["name"]);
$uploadOk = 1;
$videoFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Kiểm tra xem file video có tồn tại không
if (file_exists($targetFile)) {
    echo "Xin lỗi, file đã tồn tại.";
    $uploadOk = 0;
}

// Kiểm tra kích thước file, giới hạn là 100MB
if ($_FILES["video"]["size"] > 100000000) {
    echo "Xin lỗi, file quá lớn.";
    $uploadOk = 0;
}

// Cho phép chỉ tải lên các định dạng video như mp4, avi, etc.
if ($videoFileType != "mp4" && $videoFileType != "avi" && $videoFileType != "mov" && $videoFileType != "wmv") {
    echo "Xin lỗi, chỉ cho phép tải lên các file video MP4, AVI, MOV, hoặc WMV.";
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    echo "Tải lên không thành công.";
} else {
    if (move_uploaded_file($_FILES["video"]["tmp_name"], $targetFile)) {
        // Lưu thông tin video vào cơ sở dữ liệu
        $title = $_POST['title'];
        $description = $_POST['description'];
        $videoURL = $targetFile;
        $userId = 1; // ID của người dùng, bạn có thể thay đổi phù hợp với việc xác định người dùng đang tải lên

        $sql = "INSERT INTO videos (title, description, video_url, user_id) VALUES ('$title', '$description', '$videoURL', $userId)";
        if ($conn->query($sql) === TRUE) {
            echo "Tải lên thành công.";
        } else {
            echo "Lỗi: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Đã xảy ra lỗi khi tải lên file.";
    }
}

$conn->close();
?>
