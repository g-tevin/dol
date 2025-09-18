<?php
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require __DIR__ . '/../../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userEmail = $_POST["email"] ?? '';
    $userName  = $_POST["name"] ?? 'Friend'; 

    // ✅ Validate email
    if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address");
    }

    // ✅ NEW: Connect to database (update with your credentials)
    $conn = new mysqli("localhost", "root", "0000", "dbpro");
    if ($conn->connect_error) {
        die("DB Connection failed: " . $conn->connect_error);
    }

    // ✅ NEW: Insert user into DB
    $stmt = $conn->prepare("INSERT INTO users (username, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $userName, $userEmail);
    if (!$stmt->execute()) {
        echo "Database error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.gmail.com';                       
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'tevin.mwangi@strathmore.edu';          
        $mail->Password   = 'excs eilc hmms hjgf';   // ✅ removed trailing space
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
        $mail->Port       = 465;                                    

        //Recipients
        $mail->setFrom('tevin.mwangi@strathmore.edu', 'George Tevin');
        $mail->addAddress($userEmail, $userName);     //Send to user input

        //Content
        $mail->isHTML(true);                                  
        $mail->Subject = 'Welcome to GeoLink!';
        $mail->Body    = "Hello " . htmlspecialchars($userName) .
         ",<br><br>Welcome to the Project
         <br> You are now a valued member of GeoLink and
         as such we will help you to help us reach a better future! 
         <br><br>Warm Regards, <br> George Tevin Muigai"; // ✅ fixed variable

        $mail->send();
        echo 'Message has been sent to ' . htmlspecialchars($userEmail);
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    // Simple form to collect name + email
    echo "<form method='POST'>
            <input type='text' name='name' placeholder='Enter your name' required><br><br>
            <input type='email' name='email' placeholder='Enter your email' required><br><br>
            <button type='submit'>Send Test Email</button>
          </form>";
}
