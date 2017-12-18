  <?php
include_once('../php/class/Paginator.php');
include_once('../php/class/Database.php');
//String of database connection  
$connection = Database::getConnection();

/**
* Load Client table
*/
function loadLink($sql){
    global $connection;

    $result =  mysqli_query($connection, $sql);
    $number_of_results = mysqli_num_rows($result);
    echo "<div class='pagination'>";

    for($page = 1; $page <= $number_of_results / 25; $page++){
        echo  "<a href='mainclientes.php?pg=$page'>$page</a>";
    }
    echo "</div>";
}
/**
 * Load informations of client table
 */
function loadClient($page){
    global $connection;

    $startResult = ($page-1)*25;
    $result = $connection->query("SELECT * FROM customer ORDER BY name asc limit $startResult,25");

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            
             echo "<tr><td><a class='linkname' href='../Pages/mainprofile.php?profile=".$row["idcustomer"]."&type=c'>".utf8_encode($row["name"])."</a></td>";
             echo "<td>".$row["forms_answereds"]."</td>";
             echo "<td>".$row["tecnical_visits"]."</td>";
             echo "<td>".$row["evaluation_value"]."%</td>";
             echo "<td>".$row["efetviness"]."%</td>";
            }
    }
}

/**
 * Load informations of employeers table
 */
function loadEmployers($page){
    global $connection;
    
    $startResult = ($page-1)*25;
    $result = $connection->query("SELECT * FROM employee ORDER BY idform asc limit $startResult,25");
    
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc())
        {    
             echo "<tr><td><a class='linkname' href='../Pages/mainprofile.php?profile=".$row["idemployee"]."&type=e'>".utf8_encode($row["name"])."</a></td>";
             echo "<td>".$row["note_avarage"]."</td>";
             echo "<td>".$row["issue_sol_avarage"]."%</td>";
        }
    }
}

function loadForms($page){
    global $connection;
    
    $startResult = ($page-1)*25;
    $result = $connection->query("SELECT * FROM forms ORDER BY idform asc limit $startResult,25");
    
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc())
        {    
            echo "<tr><td><a class='linkname' href='../Pages/mainprofile.php?profile=".$row["idcustomer"]."&type=e'>".$row["name"]."</a></td>";
            echo "<td>".$row2["name"]."</td>";

            if($row["evaluation_value"] == 5)  echo "<td>5 - Excelente</td>";
            else if($row["evaluation_value"] == 4)  echo "<td>4 - Muito bom</td>";
            else if($row["evaluation_value"] == 3)  echo "<td>3 - Bom</td>";
            else if($row["evaluation_value"] == 2)  echo "<td>2 - Regular</td>";
            else echo "<td>1 - Ruim</td>";

            if($row["issue_solve"] == "yes"){
                echo "<td>Sim</td>";
            }
            else {
                echo "<td>Não</td>";
            }

            echo "<td>".utf8_encode($row["commentary"])."</td>";
            echo "<td>".$row["request_sent"]."</td>";
            echo "<td>".$row["request_answered"]."</td>";
        }
    }
}

///Load Forms table
function LoadFormss(){
    global $connection;
    $result = $connection->query("select idcustomer, idemployee,evaluation_value,issue_solve,commentary, request_sent, request_answered from form order by idform asc");
     if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $result2 = $connection->query("select name from customer where V11_ID =".$row['idcustomer']);
            $row2 = $result2->fetch_assoc();
            echo "<tr><td><a class='linkname' href='../Pages/mainprofile.php?profile=".$row["idcustomer"]."&type=e'>".$row2["name"]."</a></td>";
            $result2 = $connection->query("select name from employee where V11_code =". $row["idemployee"]);
            $row2 = $result2->fetch_assoc();
            echo "<td>".$row2["name"]."</td>";
            if($row["evaluation_value"] == 5)  echo "<td>5 - Excelente</td>";
            else if($row["evaluation_value"] == 4)  echo "<td>4 - Muito bom</td>";
            else if($row["evaluation_value"] == 3)  echo "<td>3 - Bom</td>";
            else if($row["evaluation_value"] == 2)  echo "<td>2 - Regular</td>";
            else echo "<td>1 - Ruim</td>";
                
            if($row["issue_solve"] == "yes"){
                echo "<td>Sim</td>";
            }
            else {
                echo "<td>Não</td>";
            }
            echo "<td>".utf8_encode($row["commentary"])."</td>";
            echo "<td>".$row["request_sent"]."</td>";
            echo "<td>".$row["request_answered"]."</td>";
        }
    }
}
/**
* Check in mainprofile's GET what is the table of database referenced
*/
function GetTableReference($type){
    if($type == "c")return "customer";
    else return "employee";
}
function LoadCustomerProfile($id, $name){
    global $connection;
    $result = $connection->query("SELECT * FROM form WHERE id$name = $id");
     if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
                $get = $connection->query("SELECT name from $name where id$name = $id");              
                $name = $get->fetch_assoc();
                echo "<td>".$name["name"]."</td>";
        }
    }
}
function LoadDataFrom($id, $table){
    global $connection;
    $sql = "SELECT * FROM $table WHERE id$table = $id";
    $result = $connection->query("SELECT * FROM $table WHERE id$table = $id");
    $output = $result->fetch_assoc();
    if($result->num_rows > 0){return $output;}
    else{return null;}
    }
/**
* Load table referenced to profile historic
*/
function LoadTableColuns($kind){
if($kind == "c"){
    echo "<td>Nome do técnico</td>";
}
else if($kind == "e"){
    echo "<td id='nomecli'>Nome do cliente</td>";
}
else{
    echo "";
}
echo "<td id='nota'>Nota</td>";
echo "<td>Problema resolvido ?</td>";
echo "<td>Comentário</td>";
echo "<td>Data de envio da pesquisa</td>";
echo "<td>Data de resposta da pesquisa</td>";
}
/**
 * carrega o número de registros das tabelas
 */
function GetNumberFromQuery($sql1){
    $sql = $sql1;
    global $connection;
    $result = $connection->query($sql);
     if($result->num_rows > 0){
         $row = $result->fetch_assoc();
       foreach($row as $val){
           return $val;
       }
     }
     else{
        return 0;
     }
}
/**
 * carrega o nome de um usuario através do seu ID
 */
function GetNameFromBD($id, $table){
    global $connection;
    $get = $connection->query("SELECT name FROM $table WHERE id$table = $id");
    $name = $get->fetch_assoc();
    return $name["name"];
}
/**
 * Carrega os emails registrados de um cliente
 */
function GetEmailsFromBD($id, $table){
    global $connection;
    $get = $connection->query("SELECT emails FROM $table WHERE id$table = $id");
    $name = $get->fetch_assoc();
    $val_to_return = "";
    foreach($name as $single_mail){
        return $single_mail;
    }
}
/** 
 * Carrega o historico de um cliente ou funcionário
*/
function LoadHistoric($from, $id_vip){
    global $connection;
    $result = $connection->query("SELECT idcustomer, idemployee,evaluation_value,issue_solve,commentary, request_sent, request_answered from form where id".$from." = $id_vip order by idform desc");
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $result2 = $connection->query("select name from customer where V11_ID =".$row['idcustomer']);
            $row2 = $result2->fetch_assoc();
            echo "<tr><td><a class='linkname' href='../Pages/mainprofile.php?profile=".$row["idcustomer"]."&type=e'>".$row2["name"]."</a></td>";
            if($row["evaluation_value"] == 5)  echo "<td>5 - Excelente</td>";
            else if($row["evaluation_value"] == 4)  echo "<td>4 - Muito bom</td>";
            else if($row["evaluation_value"] == 3)  echo "<td>3 - Bom</td>";
            else if($row["evaluation_value"] == 2)  echo "<td>2 - Regular</td>";
            else echo "<td>1 - Ruim</td>";
                
            if($row["issue_solve"] == "yes"){
                echo "<td>Sim</td>";
            }
            else {
                echo "<td>Não</td>";
            }
            echo "<td>".utf8_encode($row["commentary"])."</td>";
            echo "<td>".$row["request_sent"]."</td>";
            echo "<td>".$row["request_answered"]."</td></tr>";
        }
    }
}
?>