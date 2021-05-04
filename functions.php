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

function findPlanByDate($date)
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
        $sql .= <<<EOM
                NULL
            ORDER BY
                due_date ASC;
            EOM;
    } else {
        $sql .= <<<EOM
                NOT NULL
            ORDER BY
                completion_date DESC;
            EOM;
    }

    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function findById ($id) 
{
    $dbh = connectDb();
    $sql = <<<EOM
    SELECT
        *
    FROM
        plans
    WHERE
        id = :id; 
    EOM;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updatePlan ($id, $title, $date)
{
    $dbh = connectDb();
    $sql = <<<EOM
    UPDATE
        plans
    SET
        title = :title,
        due_date = :due_date
    WHERE
        id = :id;
    EOM;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':due_date', $date, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

function updateValidate ($title, $date, $plan)
{
    $errors = [];
    if ($title == '') {
        $errors[] = MSG_TITLE_REQUIRED;
    }
    if ($date == '') {
        $errors[] = MSG_DATE_REQUIRED;
    }
    if ($title == $plan['title'] && $date == $plan['due_date']) {
        $errors[] = MSG_NO_CHANGE;
    }
    return $errors;
}

function insertPlan($title, $date)
{
    $dbh = connectDb();
    $sql = <<<EOM
    INSERT INTO
        plans
        (title, due_date)
        VALUE
        (:title, :due_date)
    EOM;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':due_date', $date, PDO::PARAM_STR);
    $stmt->execute();
}

function insertValidate($title, $date)
{
    $errors = [];
    if ($title == '') {
        $errors[] = MSG_TITLE_REQUIRED;
    }
    if ($date == '') {
        $errors[] = MSG_DATE_REQUIRED;
    }
    return $errors;
}

function createErrMsg($errors)
{
    $err_msg = "<ul>\n";
    foreach ($errors as $err) {
        $err_msg .= "<li>" . h($err) . "</li>\n";
    }
    $err_msg .= "</ul>\n";
    return $err_msg;
}

function dateColor($plan) {
    if($plan['due_date'] < date("Y-m-d")) {
        echo 'class="expired"';
    }
}

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

?>