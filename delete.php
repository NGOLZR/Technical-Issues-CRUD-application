// Get device ID from URL parameter
<?php
echo "<head>";
echo "<style>
    .confirmation-box {
        background: linear-gradient(to right, #ffd700, #87cefa);
        border-radius: 10px;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.5);
        color: #ffffff;
        font-size: 18px;
        margin: 0 auto;
        max-width: 400px;
        padding: 20px;
        text-align: center;
    }
    .confirmation-box h2 {
        margin-top: 0;
    }
    .confirmation-box button {
        background-color: #000000;
        border: none;
        border-radius: 40px;
        color: #ffffff;
        cursor: pointer;
        display: inline-block;
        font-size: 16px;
        margin-top: 20px;
        padding: 10px 40px;
        transition: background-color 0.3s ease;
    }
    .confirmation-box button:hover {
        background-color: #444444;
    }
</style>";
echo "</head>";
?>
if (!isset($_GET['id'])) {
    echo "<p>Device ID not provided.</p>";
    exit;
}
$device_id = $_GET['id'];

// Load saved issues from file
$file = fopen('issues.txt', 'r');
if (!$file) {
    echo "<p>Failed to load issues file.</p>";
    exit;
}
$issues = [];
while (($line = fgets($file)) !== false) {
    $fields = explode("\t", $line);
    if (count($fields) >= 2) {
        $issues[$fields[0]] = [
            'device_id' => $fields[0],
            'problem_description' => $fields[1],
        ];
    }
}
fclose($file);

// Remove issue with matching device ID
if (isset($issues[$device_id])) {
    if(isset($_POST['delete']) && $_POST['delete'] == 'yes') {
        unset($issues[$device_id]);

        // Save updated issues to file
        $file = fopen('issues.txt', 'w');
        if (!$file) {
            echo "<p>Failed to save issues file.</p>";
            exit;
        }
        foreach ($issues as $issue) {
            fwrite($file, "{$issue['device_id']}\t{$issue['problem_description']}\n");
        }
        fclose($file);

        // Redirect back to display table
        header('Location: index.php');
        exit;
    } else {
        echo "<script>
        let result = confirm('Are you sure you want to permanently delete this issue?');
        if(result == true) {
            document.forms['delete-form'].submit();
        } else {
            window.location.href = 'index.php';
        }
        </script>";
        echo "<form name='delete-form' method='post'>";
        echo "<input type='hidden' name='delete' value='yes'>";
        echo "</form>";
    }
} else {
    echo "<p>Issue not found.</p>";
    exit;
}
?>