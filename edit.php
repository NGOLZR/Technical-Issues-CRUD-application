<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <title>Technical Issues</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Technical Issues</h1>
    <?php
    // Check if form was submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get device ID and issue from form submission
        $device_id = $_POST['device-id'];
        $issue = $_POST['issue'];
    
        // Load saved issues from file
        $issues = [];
        $file = fopen('issues.txt', 'r');
        if ($file) {
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
        }
        
        // Update issue with new data
        if (isset($issues[$device_id])) {
            $issues[$device_id]['problem_description'] = $issue;
            
            // Save updated issues to file
            $file = fopen('issues.txt', 'w');
            foreach ($issues as $issue) {
                fwrite($file, "{$issue['device_id']}\t{$issue['problem_description']}\n");
            }
            fclose($file);
            
            // Redirect back to display table
            header('Location: index.php');
        } else {
            echo "<p>Issue not found.</p>";
        }
    } else {
        // Get issue ID from URL parameter
        $device_id = $_GET['id'];
    
        // Load saved issues from file
        $issues = [];
        $file = fopen('issues.txt', 'r');
        if ($file) {
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
        }
        
        // Display issue data in form
        if (isset($issues[$device_id])) {
            $issue = $issues[$device_id]['problem_description'];
            echo "<form method='post' action=''>
                <label for='device-id'>Device ID:</label>
                <input type='text' id='device-id' name='device-id' value='$device_id' readonly>
                <br>
                <label for='issue'>Issue:</label>
                <textarea id='issue' name='issue'>$issue</textarea>
                <br>
                <input type='submit' value='Update'>
            </form>";
        } else {
            echo "<p>Issue not found.</p>";
        }
    }
    ?>
</body>
</html>
