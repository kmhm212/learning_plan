<?php

require_once __DIR__ . '/config.php';

function connectDB() 
{
    try {
        return new PDO(
            DSN,
            USER,
            PASSWORD,
            [PDO::ATTR_ERRMODE =>
            PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
}

function findTaskByDate($date)
{
    $dbh = connectDb();

    $sql = <<<EOM
    SELECT
        *
    FROM
        plans
    WHERE
        completion_date IS 
    EOM;

    if ($date == 'NULL') {
        $sql .= 'NULL;';
    } else {
        $sql .= 'NOT NULL;';
    }
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function insertTask($title, $date) {
    $dbh = connectDb();
    $sql = <<<EOM
    INSERT INTO
        plans
        (title, due_date)
        VALUE
        (:title, :date)
    EOM;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt->execute();
}

function insertValidate($title,$date) {
    $errors = [];
    if ($title == '') {
        $errors[] = MSG_TITLE_REQUIRED;
    }
    if ($date == '') {
        $errors[] = MSG_DATE_REQUIRED;
    }
    return $errors;
}

function createErrMsg($errors) {
    $err_msg = "<ul>\n";
    foreach ($errors as $err) {
        $err_msg .= "<li>" . h($err) . "</li>\n";
    }
    $err_msg .= "</ul>\n";
    return $err_msg;
}

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

?>