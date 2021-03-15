<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/api/config/Database.php';

class ResetPassword
{
    private $conn;
    private $table = "password_reset";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function requestReset($email)
    {
        $selector = bin2hex(random_bytes(8));
        $token = random_bytes(32);

        $url = "www.schede.herokuapp.com/resetpwd.php?s=" . $selector . "&t=" . bin2hex($token);

        bin2hex($token);

        $exp = date("U") + 3600;

        $qry = "DELTE FROM " . $this->table . " WHERE email = ?";

        $stmt = $this->conn->prepare($qry);
        $stmt->bindParam(1, $email);
        $stmt->execute();

        $qry = "INSERT INTO " . $this->table . " (email, selector, token, expires) VALUES (:email, :selector, :token, :expires)";

        $hashedToken = password_hash($token, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare($qry);
        $stmt->execute(
            array()
        );

        // $stmt->execute(
        //     array(
        //         'name' => $data['name'],
        //         'surname' => $data['surname'],
        //         'email' => $data['email'],
        //         'age' => $data['age'],
        //         'status' => $data['status']
        //     )
        // );
    }

    public function sendMail($email, $url)
    {
        $to = $email;

        $subject = "Rest your password for Schede";

        $message = <<<MSG
            <p>We recieved a password. The link to reset your password is below.
             If you did not make request to reset your password,
              please ignore this email</p

            <p>Click <a href="$url">this link</a> to reset your password</p>
        MSG;

        $headers = "From: Schede <schede@gmail.com>\r\n";
        $headers .= "Reply-To: schede@gmail.com\r\n";
        $headers .= "Content-type: text/html\r\n";

        mail($to, $subject, $message, $headers);
    }
}
