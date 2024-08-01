<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test_db";
$posts_per_page = 10;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current page number from URL parameters, default to 1 if not set
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $posts_per_page;

$sql = "SELECT id, headline, paragraph, images FROM blogs LIMIT $posts_per_page OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blogs</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #95c2bad4;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: auto;
        }
        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }
        .card-content {
            padding: 15px;
        }
        .card-content h3 {
            margin-top: 0;
            color: #333;
        }
        .card-content p {
            color: #666;
            /* Ensure the paragraph spans multiple lines */
            display: block;
            margin: 0;
            padding: 10px 0;
        }
        .slick-slide img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }
        .pagination {
            text-align: center;
            margin: 20px 0;
        }
        .pagination a {
            color: #007bff;
            text-decoration: none;
            padding: 8px 16px;
            border: 1px solid #ddd;
            margin: 0 4px;
            border-radius: 4px;
        }
        .pagination a:hover {
            background-color: #ddd;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.image-slider').slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 1,
                adaptiveHeight: true
            });

            $("[data-fancybox='gallery']").fancybox({
                loop: true,
                buttons: [
                    'zoom',
                    'slideShow',
                    'thumbs',
                    'close'
                ]
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h2>Blogs</h2>
        <div class="grid">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $headline = htmlspecialchars($row["headline"]);
                    $paragraph = htmlspecialchars($row["paragraph"]);
                    $imageSrcs = json_decode($row["images"], true); // Decode JSON to array

                    echo '<div class="card">';
                    if (is_array($imageSrcs) && !empty($imageSrcs)) {
                        // Display the images in a slider with Fancybox for full-screen view
                        echo '<div class="image-slider">';
                        foreach ($imageSrcs as $imageSrc) {
                            echo '<div><a href="' . htmlspecialchars($imageSrc) . '" data-fancybox="gallery" data-caption="' . htmlspecialchars($headline) . '">
                                    <img src="' . htmlspecialchars($imageSrc) . '" alt="Blog Image">
                                  </a></div>';
                        }
                        echo '</div>';
                    }
                    echo '<div class="card-content">';
                    echo '<h3>' . $headline . '</h3>';
                    echo '<p>' . $paragraph . '</p>'; // Show full text
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "<p>No blogs found.</p>";
            }
            ?>
        </div>
        <div class="pagination">
            <?php
            // Get the total number of blog posts for pagination
            $count_sql = "SELECT COUNT(*) as total FROM blogs";
            $count_result = $conn->query($count_sql);
            $total_posts = $count_result->fetch_assoc()['total'];
            $total_pages = ceil($total_posts / $posts_per_page);

            for ($i = 1; $i <= $total_pages; $i++) {
                echo '<a href="?page=' . $i . '">' . $i . '</a>';
            }
            ?>
        </div>
    </div>
    <?php $conn->close(); ?>
</body>
</html>
