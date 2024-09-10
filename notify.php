<?php
// require "./config.php";

// // Retrieve the broadcasts from the database
// $query = "SELECT * FROM broadcast";
// $result = $conn->query($query);

// if ($result) {
//     // while ($row = $result->fetch_assoc()) {
//     //     $broadcastId = $row['id'];
//     //     // $startDateTime = $row['starts_at'];
//     //     $startDateTime = date("Y-m-d", strtotime("+1 minutes"))

//     //     // Convert startDateTime to Unix timestamp
//     //     $timestamp = strtotime($startDateTime);

//     //     // Calculate the time difference between current time and startDateTime
//     //     $timeDiff = $timestamp - time();

//     //     // Create a cron job for the broadcast
//     //     $cronJobCommand = "/opt/lampp/htdocs/pd-finalProject/scripts/your_script.php broadcast_id=$broadcastId";
//     //     $cronJobSchedule = "{$timeDiff} * * * *"; // Run the cron job at the specified time

//     //     // Add the cron job to the crontab
//     //     exec("echo '{$cronJobSchedule} {$cronJobCommand}' | crontab -");

//     //     // Output a message for each cron job created
//     //     echo "Cron job created for broadcast ID $broadcastId. Scheduled at: " . date('Y-m-d H:i:s', $timestamp) . "\n";
//     // }

//     $startDateTime = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." +1 minutes"));

//     // Convert startDateTime to Unix timestamp
//     $timestamp = strtotime($startDateTime);

//     // Calculate the time difference between current time and startDateTime
//     $timeDiff = $timestamp - time();

//     // Create a cron job for the broadcast
//     $cronJobCommand = "/opt/lampp/htdocs/pd-finalProject/scripts/notify_users.php";
//     $cronJobSchedule = "{$timeDiff} * * * *"; // Run the cron job at the specified time

//     // Generate a unique identifier for the cron job
//     $cronJobIdentifier = md5($cronJobCommand . $cronJobSchedule);

//     // Create the cron job using crontab command
//     $command = "(crontab -l; echo '{$cronJobSchedule} {$cronJobCommand}') | crontab -";
//     $output = shell_exec($command);

//     // Add the cron job to the crontab
//     // exec("echo '{$cronJobSchedule} {$cronJobCommand}' | crontab - > /opt/lampp/htdocs/pd-finalProject/scripts/logfile 2>&1");

//     $result->free(); // Free the result set
// } else {
//     echo "Failed to retrieve broadcasts from the database: " . $conn->error;
// }

// $conn->close(); // Close the MySQL connection
?>

<?php
$crontabFile = '/var/spool/cron/crontabs/prabin-stha'; // Replace with the actual path to your crontab file
$phpScript = '/path/to/your/php/script.php'; // Replace with the path to your PHP script

// Connect to your database and fetch the list of `starts_at` values greater than the current time
$host = 'localhost';
$dbName = 'secure_auth';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the current time in the same format as `starts_at` column (assuming your database uses UTC)
    $currentTime = gmdate('Y-m-d H:i:s');

    // Fetch the `starts_at` values greater than the current time
    $query = $pdo->prepare("SELECT starts_at FROM broadcast WHERE starts_at > :currentTime");
    $query->bindParam(':currentTime', $currentTime);
    $query->execute();
    $startsAtList = $query->fetchAll(PDO::FETCH_COLUMN);

    // Open the crontab file for editing
    $file = fopen($crontabFile, 'a');

    if ($file) {
        foreach ($startsAtList as $startsAt) {
            // Create the cron job entry to execute the PHP script
            $cronJobEntry = date('i G d m', strtotime($startsAt)) . " php $phpScript" . PHP_EOL;

            // Append the cron job entry to the crontab file
            fwrite($file, $cronJobEntry);
        }

        // Close the file
        fclose($file);

        echo 'Cron job entries added successfully.';
    } else {
        echo 'Unable to open the crontab file.';
    }
} catch (PDOException $e) {
    echo 'Error connecting to the database: ' . $e->getMessage();
}
?>