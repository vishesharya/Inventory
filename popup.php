<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Popup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        /* Popup Modal */
        .popup {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
        }

        .popup-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 5px;
            width: 80%;
            max-width: 600px;
            display: flex;
            flex-wrap: wrap;
        }

        /* Close Button */
        .close {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
        }

        .popup-left,
        .popup-right {
            flex: 1;
            min-width: 250px;
        }

        /* Image Section */
        .popup-left img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        /* Text Section */
        .popup-right {
            padding: 20px;
        }

        h2 {
            margin-top: 0;
        }

        .action-btn {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .popup-content {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<?php
// Example PHP variables for content
$heading = "Your Heading";
$details = "Some details about the content. This section can include more descriptive text.";
$image = "your-image.jpg"; // Path to the image
?>

<!-- Popup Modal -->
<div id="popupModal" class="popup">
    <div class="popup-content">
        <span class="close">&times;</span>
        <div class="popup-left">
            <img src="<?php echo $image; ?>" alt="Popup Image">
        </div>
        <div class="popup-right">
            <h2><?php echo $heading; ?></h2>
            <p><?php echo $details; ?></p>
            <button class="action-btn">Click Me</button>
        </div>
    </div>
</div>

<script>
// Get elements
var popup = document.getElementById("popupModal");
var span = document.getElementsByClassName("close")[0];

// Automatically show the popup when the page loads
window.onload = function() {
    popup.style.display = "block";
}

// Close the popup when the "x" is clicked
span.onclick = function() {
    popup.style.display = "none";
}

// Close the popup when clicking outside of the popup content
window.onclick = function(event) {
    if (event.target == popup) {
        popup.style.display = "none";
    }
}
</script>

</body>
</html>
