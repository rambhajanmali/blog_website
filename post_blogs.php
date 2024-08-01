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
        foreach ($selected_ids as $id) {
            // Retrieve the headline, paragraph, and image for the current ID
            $sql = "SELECT headline, paragraph, images FROM uploads WHERE id='$id'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $headline = $row['headline'];
                $paragraph = $row['paragraph'];
                $image = $row['images'];

                // Insert the headline, paragraph, and image into the blogs table
                $insert_sql = "INSERT INTO blogs (headline, paragraph, images) VALUES ('$headline', '$paragraph', '$image')";
                if ($conn->query($insert_sql) === TRUE) {
                    // Delete the inserted data from the uploads table
                    $delete_sql = "DELETE FROM uploads WHERE id='$id'";
                    $conn->query($delete_sql);
                } else {
                    echo "Error: " . $insert_sql . "<br>" . $conn->error;
                }
            }
        }
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
