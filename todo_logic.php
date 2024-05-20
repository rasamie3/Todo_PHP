<?php
    function connect(){
        $db_host = getenv("DB_HOST");
        $db_port = getenv("DB_PORT");
        $db_dbname = getenv("DB_DATABASE");
        $db_user = getenv("DB_USER");
        $db_pass = getenv("DB_PASSWORD");

        $connection = pg_connect("host=$db_host port=$db_port dbname=$db_dbname user=$db_user password=$db_pass");
        return $connection;
    }


    function show_done(){
        if (isset($_POST["select"]) ){
            $con = connect();
            if (!$con) {
                echo "Error: Connection Database";
                return;
            }


            if ($_POST["select"]=="true") {
                $sql = "SELECT * FROM TODO WHERE is_done=1";
                $result = pg_query($con, $sql);

                echo "<h3>done todo</h3>";
                
                if ($result) {
                    while ($row = pg_fetch_assoc($result)) {
                        $id = htmlspecialchars($row["id"]);
                        $task = htmlspecialchars($row["task"]);
                        $created_at = htmlspecialchars($row["created_at"]);
                        $is_done = htmlspecialchars($row["is_done"]);
                        
                        echo "<li>$task | added on $created_at<button name='task' value='$id-$is_done'>Mark as Not Done</button></li>";
                        
                        $_SESSION["todo_done"][] = $task;
                    }   
                } else {
                    echo "Error: Check you query";
                }
            }

            pg_close($con);
        }
    }

    function set_done(){
        $con = connect();
        if (!$con) {
            echo "Error: Connection Database";
            return;
        }
        if (isset($_POST["task"])) {
            $id_isd = explode('-', $_POST["task"]);
            $id = $id_isd[0];
            $is_done = $id_isd[1];

            if ($is_done == 1) {
                $is_done = 0;
            }
            else {
                $is_done = 1;
            }
            
            $sql = "UPDATE TODO SET is_done=$is_done WHERE id=$1";
            $result = pg_query_params($con, $sql, array($id));
       
            if (!$result) {
                echo "Error: update query error";
            }
        }

        pg_close($con);
    }

    function get_undone_todo(){
        $con = connect();
        if (!$con) {
            echo "Error: Connection Database";
            return;
        }

        $sql = "SELECT * FROM TODO WHERE is_done=0";
        $result = pg_query($con, $sql);
        
        echo "<h3>Undone todo</h3>";

        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $id = htmlspecialchars($row["id"]);
                $task = htmlspecialchars($row["task"]);
                $created_at = htmlspecialchars($row["created_at"]);
                $is_done = htmlspecialchars($row["is_done"]);
                                
                echo "<li>$task | add on $created_at <button name='task' value='$id-$is_done'>Mark as Done</button></li>";

                $_SESSION["todo_list"][] = $task;
            }
        }else{
            echo "Error: Select query error";
        }
        
        pg_close($con);
    }

    function insert_todo() {
        $con = connect();
        if (!$con) {
            echo "Error: Connection Database";
        }
        if (isset($_POST["todo"]) && $_POST["todo"]!=="") {
            $task = $_POST["todo"];
            $sql = "INSERT INTO TODO (task) VALUES ($1)";
            $result = pg_query_params($con, $sql, array($task));

            if (!$result) {
                echo "Error: Insert query error";
            }
        }
        pg_close($con);
    }