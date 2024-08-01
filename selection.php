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

$sql = "SELECT id, headline, paragraph, images FROM uploads";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Select Inputs to Post as Blogs</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 20px;
            color: #fff;
            background: linear-gradient(135deg,#80BCBD,#AAD9BB,#D5F0C1,#F9F7C9);
            background-size: 400% 400%;
            animation: gradientBackground 5s ease infinite;
        }

        @keyframes gradientBackground {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: black;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            /* background-color: #fff; */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            color: black;
        }
        
        th, td {
            padding: 16px;
            text-align: left;
        }
        
        th {
            background-color: #5392F9;
            color: white;
            font-size: 18px; /* Increased font size */
        }
        
        tr:nth-child(even) {
            background-color: transparent; /* Blue-green color */
        }
        
        tr:nth-child(odd) {
            background-color: #f0f7f7; /* Lighter blue-green color */
        }
        
        .image-preview {
            max-width: 150px; 
            height: auto;
            margin: 5px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .more-images {
            display: none;
            margin-top: 10px;
        }
        
        .show-more {
            cursor: pointer;
            color: #5392F9;
            text-decoration: underline;
        }
        
        .show-more:hover {
            text-decoration: none;
        }
        
        .btn-submit {
            display: block;
            width: 50%;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #5392F9;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
        }
        
        .btn-submit:hover {
            background-color: #4178d3;
        }
        
        @media (max-width: 768px) {
            th, td {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <h2>Select Inputs to Post as Blogs</h2>
    <form method="post" action="post_blogs.php">
        <table>
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Headline</th>
                    <th>Paragraph</th>
                    <th>Images</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $imageSrcs = json_decode($row["images"], true); // Decode JSON to array
                        if (!is_array($imageSrcs)) {
                            $imageSrcs = []; // If decoding fails, use an empty array
                        }
                        echo '<tr>';
                        echo '<td><input type="checkbox" name="selected_inputs[]" value="' . $row["id"] . '"></td>';
                        echo '<td>' . htmlspecialchars($row["headline"]) . '</td>';
                        echo '<td>' . htmlspecialchars($row["paragraph"]) . '</td>';
                        echo '<td>';
                        echo '<div class="image-preview-container">';
                        for ($i = 0; $i < min(3, count($imageSrcs)); $i++) {
                            echo '<img src="' . htmlspecialchars($imageSrcs[$i]) . '" class="image-preview" alt="Image">';
                        }
                        if (count($imageSrcs) > 3) {
                            echo '<div class="more-images">';
                            for ($i = 3; $i < count($imageSrcs); $i++) {
                                echo '<img src="' . htmlspecialchars($imageSrcs[$i]) . '" class="image-preview" alt="Image">';
                            }
                            echo '</div>';
                            echo '<span class="show-more" onclick="toggleMoreImages(this)">Show more</span>';
                        }
                        echo '</div>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4">No inputs found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
        <input type="submit" name="submit" value="Post Blogs" class="btn-submit">
    </form>
    <script>
        function toggleMoreImages(element) {
            const moreImages = element.previousElementSibling;
            if (moreImages.style.display === "none" || moreImages.style.display === "") {
                moreImages.style.display = "block";
                element.textContent = "Show less";
            } else {
                moreImages.style.display = "none";
                element.textContent = "Show more";
            }
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
