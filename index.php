<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <title>Technical Issues</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Technical Issues</h1>
    <form method="post" action="">
        <label for="device-id">Device ID:</label>
        <input type="text" id="device-id" name="device-id">
        <br>
        <label for="issue">Issue:</label>
        <textarea id="issue" name="issue"></textarea>
        <br>
        <input type="submit" value="Submit">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get device ID and issue from form submission
        $device_id = $_POST['device-id'];
        $issue = $_POST['issue'];
    
        // Save device ID and issue to a file
        $file = fopen('issues.txt', 'a');
        fwrite($file, "$device_id\t$issue\n");
        fclose($file);
    }
    
// Load saved issues from file
$issues = [];
$file = fopen('issues.txt', 'r');
if ($file) {
    while (($line = fgets($file)) !== false) {
        $fields = explode("\t", $line);
        if (count($fields) >= 2) {
            $issues[] = [
                'device_id' => $fields[0],
                'problem_description' => $fields[1],
            ];
        }
    }
    fclose($file);
}

    ?>

    <table>
        <thead>
            <tr>
                <th>Device ID</th>
                <th>Issue</th>
                <th>Action</th>
                
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($issues as $issue) {
                echo "<tr><td>{$issue['device_id']}</td><td>{$issue['problem_description']}</td><td><a href='update.php?id={$issue['device_id']}' class='action-button'>Update</a> | <a href='edit.php?id={$issue['device_id']}' class='action-button'>Edit</a> |<a href='delete.php?id={$issue['device_id']}' class='action-button'>Delete</a></td></tr>";
            }
            ?> 
        </tbody>
    </table>
</body>
</html>
