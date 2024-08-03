<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    if (!empty($_POST['selected_inputs'])) {
        $selected_ids = $_POST['selected_inputs'];
        
        // Prepare statement for insertion
        $stmt_insert = $conn->prepare("INSERT INTO blogs (headline, paragraph, images) VALUES (?, ?, ?)");
        
        // Prepare statement for deletion
        $stmt_delete = $conn->prepare("DELETE FROM uploads WHERE id=?");

        foreach ($selected_ids as $id) {
            // Retrieve the headline, paragraph, and image for the current ID
            $stmt_select = $conn->prepare("SELECT headline, paragraph, images FROM uploads WHERE id=?");
            $stmt_select->bind_param("i", $id);
            $stmt_select->execute();
            $result = $stmt_select->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $headline = $row['headline'];
                $paragraph = $row['paragraph'];
                $image = $row['images'];

                // Bind parameters and execute insertion
                $stmt_insert->bind_param("sss", $headline, $paragraph, $image);
                if ($stmt_insert->execute()) {
                    // Bind parameter and execute deletion
                    $stmt_delete->bind_param("i", $id);
                    $stmt_delete->execute();
                } else {
                    echo "Error: " . $stmt_insert->error;
                }
            }
            $stmt_select->close();
        }

        $stmt_insert->close();
        $stmt_delete->close();
        header("Location: blogs.php");
        exit(); // Ensure no further code is executed
    } else {
        echo "No inputs were selected.";
    }
} else {
    echo "Form was not submitted.";
}

$conn->close();
?>
