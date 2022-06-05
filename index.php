<?php
$db = "iteh2lb1var2";
$dsn = "mysql:host=localhost";
$user = "root";
$pass = "";
$dbh = new PDO($dsn, $user, $pass);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab1</title>
</head>

<body>
<p><strong>Мирошниченко Алина, КИУКИу-20-2, Лабораторная №1, Вариант 1<strong>
<p>
    <!-- Вывод первого запроса -->
<form action="" method="get">
    <p><strong> Вывести расписание занятий для группы </strong>
        <select name='group'>
            <option>Группа</option>
            <?php
            $sql = "Select distinct groups.title from $db.groups";
            $sql = $dbh->query($sql);
            foreach ($sql as $cell) {
                echo "<option> $cell[0] </option>";
            }
            ?>
        </select>
        <button>ОК</button>
    </p>
</form>
<!-- Вывод второго запроса -->
<form action="" method="get">
    <p><strong> Вывести расписание занятий для преподавателя </strong>
        <select name='teacher'>
            <option>Преподаватель</option>
            <?php
            $sql = "Select distinct teacher.name from $db.teacher";
            $sql = $dbh->query($sql);
            foreach ($sql as $cell) {
                echo "<option> $cell[0] </option>";
            }
            ?>
        </select>
        <button>ОК</button>
    </p>

</form>
<!-- Вывод третьего запроса -->
<form action="" method="get">
    <p><strong> Вывести расписание занятий для аудитории </strong>
        <select name='auditorium'>
            <option>Аудитория</option>
            <?php
            $sql = "Select distinct lesson.auditorium from $db.lesson";
            $sql = $dbh->query($sql);
            foreach ($sql as $cell) {
                echo "<option> $cell[0] </option>";
            }
            ?>
        </select>
        <button>ОК</button>
    </p>
</form>
<?php
/* Запрос №1 */
if (isset($_REQUEST["group"])) {
    try{
    $group = $_REQUEST["group"];
    $statement = $dbh->prepare(
        "SELECT * from $db.groups 
        join $db.lesson_groups on $db.groups.ID_Groups = $db.lesson_groups.FID_Groups 
        join $db.lesson 
        on $db.lesson_groups.FID_Lesson2=$db.lesson.ID_Lesson 
        where $db.groups.title = :groups"
    );
    $statement->execute(array('groups'=>$group));
    echo "Вывод запроса №1";
    echo "<table border ='1'>";
    echo "<tr><th>Group</th><th>Day</th><th>Number</th><th>Auditorium</th><th>Disciple</th><th>Type</th></tr>";
    while($cell=$statement->fetch(PDO::FETCH_BOTH)){
        $day = $cell[5];
        $number = $cell[6];
        $auditorium = $cell[7];
        $disciple = $cell[8];
        $type = $cell[9];
        echo "<tr><td>$group</td><td>$day</td><td>$number</td><td>$auditorium</td><td>$disciple</td><td>$type</td></tr>"; 
    }
    echo "</table>";
    }catch(PDOException $e){
        echo "Ошибка " . $e->getMessage();

    }
}

/* Запрос №2 */
if (isset($_REQUEST["teacher"])) {
    try{
    $teacher = $_REQUEST["teacher"];
    $statement = $dbh->prepare(
        "SELECT * from $db.teacher 
        join $db.lesson_teacher 
        on $db.teacher.ID_teacher = $db.lesson_teacher.FID_teacher 
        join $db.lesson 
        on $db.lesson_teacher.FID_Lesson1=$db.lesson.ID_Lesson 
        where $db.teacher.name = :teachers"
    );
    $statement->execute(array('teachers'=>$teacher));
    echo "Вывод запроса №2";
    echo "<table border ='1'>";
    echo "<tr><th>Teacher</th><th>Day</th><th>Number</th><th>Auditorium</th><th>Disciple</th><th>Type</th></tr>";
    while ($cell = $statement->fetch(PDO::FETCH_BOTH)) {
        $day = $cell[5];
        $number = $cell[6];
        $auditorium = $cell[7];
        $disciple = $cell[8];
        $type = $cell[9];
        echo "<tr><td>$teacher</td><td>$day</td><td>$number</td><td>$auditorium</td><td>$disciple</td><td>$type</td></tr>";
    }
    echo "</table>";  
    }catch(PDOException $e){
        echo "Ошибка " . $e->getMessage();

    }
}

/* Запрос №3 */
if (isset($_REQUEST["auditorium"])) {
    try{
    $auditorium = $_REQUEST["auditorium"];
    $statement = $dbh->prepare(
        "SELECT * from $db.lesson where $db.lesson.auditorium = :auditorium"
    );
    $statement->execute(array('auditorium' => $auditorium));
    echo "Вывод запроса №3";
    echo "<table border ='1'>";
    echo "<tr><th>Auditorium</th><th>Day</th><th>Number</th><th>Disciple</th><th>Type</th></tr>";
    while($cell=$statement->fetch(PDO::FETCH_BOTH)){
        $day = $cell[1];
        $number = $cell[2];
        $disciple = $cell[4];
        $type = $cell[5];
        echo "<tr><td>$auditorium</td><td>$day</td><td>$cell[2]</td><td>$disciple</td><td>$type</td></tr>";
    }
    echo "</table>";
    }catch(PDOException $e){
        echo "Ошибка " . $e->getMessage();
    }
}
?>
<p><b>Добавление нового ПЗ</b></p>
    <form method="get" action="">
        <p>День недели</p>
        <select name='day'>
            <option>Monday</option>
            <option>Tuesday</option>
            <option>Wednesday</option>
            <option>Thursday</option>
            <option>Friday</option>
            <option>Saturday</option>    
        </select>
        <p>Введите номер пары</p>
        <input name="lesson_number" type="number" value="1" min="1" max="6" step="1">
        <p>Введите номер аудитории</p>
        <input required name="auditorium">
        <p>Введите название дисциплины</p>
        <input required name="disciple">
        <p><b> Выберите преподавателя<select name="name">
            <?php 
                $sqlSelect = "SELECT * FROM $db.teacher";
                
                foreach($dbh->query($sqlSelect) as $cell)
                {   echo "<option>";
                    print($cell[1]);
                    echo "</option>";
                }

                echo "</select>" ?>
                Выберите группу<select name ="title" ><?php $sqlSelect = "SELECT * FROM $db.groups";
                
                foreach($dbh->query($sqlSelect) as $cell)
                {   echo "<option>";
                    print($cell[1]);
                    echo "</option>";
                }
               
            ?> </select>
            <button>OK</button></b></p>
        </form>
        <?php
            if(isset($_REQUEST['day']) 
            && isset($_REQUEST['lesson_number']) 
            && isset($_REQUEST['auditorium']) 
            && isset($_REQUEST['disciple']) 
            && isset($_REQUEST['name']) 
            && isset($_REQUEST['title'])){

            $week_day = $_REQUEST['day'];
            $lesson_number=$_REQUEST['lesson_number'];
            $auditorium=$_REQUEST['auditorium'];
            $disciple=$_REQUEST['disciple'];
            $type = 'Practical';
            $name=$_REQUEST['name'];
            $title=$_REQUEST['title'];
            try {
                $alter = "ALTER TABLE $db.lesson CHANGE lesson.ID_Lesson lesson.ID_Lesson INT(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = 1";
                $st= $dbh->prepare($alter);
                $st->execute();

                $sql = "INSERT INTO $db.lesson (week_day, lesson_number, auditorium, disciple, type) values ( ?, ?, ?, ?, ?)";
                $stmt= $dbh->prepare($sql);
                $stmt->execute([$week_day, $lesson_number, $auditorium, $disciple, $type]);
                
                $sql = $dbh->prepare("SELECT * from $db.teacher where $db.teacher.name = :name");
                $sql->execute(array('name' => $name));
                $sql=$sql->fetch(PDO::FETCH_BOTH);
                $teacher_id = $sql[0];
                $sql = $dbh->prepare("SELECT max(ID_Lesson) from $db.lesson");
                
                $sql->execute(array());
                $sql=$sql->fetch(PDO::FETCH_BOTH);
                $lesson_id = $sql[0];
                
                $sql = "INSERT INTO $db.lesson_teacher (FID_Teacher, FID_Lesson1) values ( ?, ?)";
                $st = $dbh->prepare($sql);
                $st->execute([$teacher_id, $lesson_id]);
            
                $sql = $dbh->prepare("SELECT * from $db.groups where $db.groups.title = :title");
                $sql->execute(array('title' => $title));
                $sql=$sql->fetch(PDO::FETCH_BOTH);
                $group_id = $sql[0];

                $sql = $dbh->prepare("SELECT max(ID_Lesson) from $db.lesson");
                $sql->execute(array());
                
                $sql=$sql->fetch(PDO::FETCH_BOTH);
                $lesson_id = $sql[0];

                $sql = "INSERT INTO $db.lesson_groups (FID_Lesson2, FID_Groups) values ( ?, ?)";
                $st = $dbh->prepare($sql);
                $st->execute([$lesson_id, $group_id]);

                echo "Занесенные данные: Преподаватель: $name, дисциплина: $disciple, аудитория: $auditorium, номер пары: $lesson_number, тип $type, день $week_day, група: $title";
                
            } catch (PDOException $e) {
                
                print "Ошибка!: " . $e->getMessage() . "<br/>";
            }
            }
            ?>       

</body>

</html>