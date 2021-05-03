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

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

?>