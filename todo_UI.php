<?php 
    ini_set('session.cookie_lifetime', 0);
    ini_set('session.gc_maxlifetime', 0);
    session_start();

    include "todo_logic.php";


    if (!isset($_SESSION["todo_list"]) && !isset($_SESSION["todo_done"])) {
        $_SESSION["todo_list"] = [];
        $_SESSION["todo_done"] = [];
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
    <div class="todo-list">
        <form action="todo.php" method="POST">
        <h1>My Todo</h1>
            <input id="todo" name="todo" type="text">
            <button id="insert" name="insert" type="submit" value="add">add</button>

            <?php
                insert_todo();
                set_done();

            ?>
        </form>

        <form action="" method="POST">
        <button name="select" type="submit" value="true">Edit done todos</button> 

            <?php
                get_undone_todo(); 
                show_done();
            ?>
        </form>
    </div>

</body>
</html>