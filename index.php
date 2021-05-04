<?php

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/config.php';

$notyet_tasks = findTaskByDate(TASK_DATE_NULL);
$done_tasks = findTaskByDate(TASK_DATE_NOT_NULL);

$title = '';
$date = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = filter_input(INPUT_POST, 'title');
    $date = filter_input(INPUT_POST, 'due_date');
    $errors = insertValidate($title, $date);
    if (empty($errors)) {
        insertTask($title, $date);
    }
}

?>

<!DOCTYPE html>
<html lang="ja">

<!-- _head.phpの読み込み -->
<?php include_once __DIR__ . "/_head.html"; ?>

<body>
    <div class="wrapper">
        <h1 class="title">学習管理アプリ</h1>
        <div class="form-area">
            <!-- エラー表示 -->
            <?php if($errors) echo (createErrMsg($errors))?>
            <form action="" method="post">
                <label for="title">学習内容</label>
                <input type="text" name="title" value="<?= h($title) ?>">
                <label for="due_date">期限日</label>
                <input type="date" name="due_date" value="<?= h($date) ?>">
                <input type="submit" class="btn submit-btn" value="追加">
            </form>
        </div>
        <div class="incomplete-area">
            <h2 class="sub-title">未達成</h2>
            <table class="plan-list">
                <thead>
                    <tr>
                        <th class="plan-title">学習内容</th>
                        <th class="plan-due-date">完了期限</th>
                        <th class="done-link-area"></th>
                        <th class="edit-link-area"></th>
                        <th class="delete-link-area"></th>
                    </tr>
                </thead>
                <tbody>

                    <!-- 未完了のデータを表示 -->
                    <?php foreach($notyet_tasks as $task): ?>
                        <tr>
                            <td class="plan-title"><?= h($task['title']) ?></td>
                            <td <?php if($task['due_date'] < date("Y-m-d")) echo ('style="color: red"')?>>
                                <?= h(date("Y/m/d", strtotime($task['due_date']))) ?></td>
                            <td><a href="done.php?id=<?= h($task['id'])?>" class="btn done-btn">完了</a></td>
                            <td><a href="edit.php?id=<?= h($task['id'])?>" class="btn edit-btn">編集</a></td>
                            <td><a href="delete.php?id=<?= h($task['id'])?>" class="btn delete-btn">削除</a></td>
                        </tr>
                    <?php endforeach ?>

                </tbody>
            </table>
        </div>
        <div class="complete-area">
            <h2 class="sub-title">完了</h2>
            <table class="plan-list">
                <thead>
                    <tr>
                        <th class="plan-title">学習内容</th>
                        <th class="plan-completion-date">完了日</th>
                        <th class="done-link-area"></th>
                        <th class="edit-link-area"></th>
                        <th class="delete-link-area"></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 完了済のデータを表示 -->
                    <?php foreach($done_tasks as $task): ?>
                        <tr>
                            <td class="plan-title"><?= h($task['title']) ?></td>
                            <td><?= h(date("Y/m/d", strtotime($task['completion_date']))) ?></td>
                            <td><a href="done.php?id=<?= h($task['id'])?>" class="btn not-done-btn">未完了</a></td>
                            <td><a href="edit.php?id=<?= h($task['id'])?>" class="btn edit-btn">編集</a></td>
                            <td><a href="delete.php?id=<?= h($task['id'])?>" class="btn delete-btn">削除</a></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
