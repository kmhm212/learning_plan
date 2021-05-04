<?php

require_once __DIR__ . '/functions.php';
$id = filter_input(INPUT_GET, 'id');
$task = findById($id);

$title = '';
$date = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = filter_input(INPUT_POST, 'title');
    $date = filter_input(INPUT_POST, 'due_date');
    $errors = updateValidate($title, $date, $task);

    if (empty($errors)) {
        updateTask($id, $title, $date);
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<?php include_once __DIR__ . '/_head.html'; ?>

<body>
    <div class="wrapper">
        <h1 class="title">学習管理アプリ</h1>
        <div class="form-area">
            <!-- エラー表示 -->
            <?php if($errors) echo (createErrMsg($errors))?>
            <form action="" method="post">
                <label for="title">編集</label>
                <input type="text" name="title" value="<?= h($task['title']) ?>">
                <label for="due_date">期限日</label>
                <input type="date" name="due_date" value="<?= h($task['due_date']) ?>">
                <input type="submit" class="btn submit-btn" value="更新">
            </form>
            <a href="index.php" class="btn return-btn">戻る</a>
        </div>
    
</body>
</html>