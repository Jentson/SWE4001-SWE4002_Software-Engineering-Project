<?php
// Check if the file is uploaded
if (isset($_FILES['files']) && $_FILES['files']['error'] === UPLOAD_ERR_OK) {
    $pdfFile = $_FILES['files'];
    $fileName = $_FILES['files']['name'];
    $fileTmpName = $_FILES['files']['tmp_name'];
    $fileType = mime_content_type($fileTmpName);

    // Allowed file types
    $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];

    if (in_array($fileType, $allowedTypes)) {
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $fileDestination = '../file/' . uniqid('', true) . '.' . $fileExtension; // Unique filename with extension

        if (move_uploaded_file($fileTmpName, $fileDestination)) {
            echo 'success';
        } else {
            error_log("Error moving file to destination.");
            echo 'error';
        }
    } else {
        echo 'invalid_type';
    }
} else {
    echo 'no_file';
}
?>