<?php
$response = array();
if(isset($_POST["email"])) {
    require_once __DIR__ . '/database.php';
    $db = new Database();

    $id = $db->get_user_id_by_email($_POST["email"]);
    if($id) {
        include __DIR__ . '/email.php';
        $code = $db->add_temp_code(3, $id, $_POST["email"]);
        $message = '
<p>Zresetuj swoje hasło na RA Work. Poniżej znajduje się link aktywacyjny:</p>
<a href="http://work.marcin-kalinowski.pl/reset-password?code=' . $code . '&id=' . $id . '">http://work.marcin-kalinowski.pl/reset-password?code=' . $code . '&id=' . $id . '</a>
        ';
        $mail = new send_email($_POST["email"], $_POST["email"], "RA Work | Resetowanie hasła", $message);
        if(!($mail->send())) {
            add_log('[ERROR] Nie udało się wysłać maila. Błąd: ' . $mail->getEmailError());
            exit('Nie udało się wysłać wiadomości e-mail.');
        }
        $response["check"] = true;
        echo json_encode($response);
    } else {
        $response["check"] = false;
        $response["message"] = "Nie znaleziono użytkownika o takim adresie e-mail";
        echo json_encode($response);
    }
} else {
    $response["check"] = false;
    $response["message"] = "Nie podano wszystkich argumentów";
    echo json_encode($response);
}