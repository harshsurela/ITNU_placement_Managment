<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "Placement");

if (isset($_SESSION['name'])) {
    $user_id = $_SESSION['id'];
    $target_dir = "resume_upload/";
    echo "<script>alert('File name: ".$_FILES["resume"]["name"]."'); </script>";
    
    $target_file = $target_dir . basename($_FILES["resume"]["name"]);
    $uploadOk = 1;
    $pdfFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $resume_name = "resume_" . $user_id;

    // Check if PDF file is a valid PDF
    if (isset($_POST["submit"])) {
        if ($pdfFileType != "pdf") {

            echo "<script>alert('File is not a PDF'); </script>";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "<script>alert('Sorry, file already exists'); </script>";
        unlink($target_file);
        $uploadOk = 1;
    }

    // Check file size
    if ($_FILES["resume"]["size"] > 500000) {
        echo "<script>alert('Sorry, your file is too large'); </script>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "<script>alert('Sorry, Your file is not uploaded'); </script>";
    } else {
        if (move_uploaded_file($_FILES["resume"]["tmp_name"], "resume_uploads/" . $resume_name . "." . $pdfFileType)) {
            // Store file information in the database
            $pdf_path = "resume_uploads/" . $resume_name . "." . $pdfFileType;
            $result = "UPDATE profile
                       SET resume='$pdf_path'
                       WHERE uid=$user_id";
            if (mysqli_query($con, $result)) {
                header("Location: profile.php");
            } else {
                echo "<script>alert('Error updating database'); </script>";
            }
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file'); </script>";
        }
    }
}
?>
