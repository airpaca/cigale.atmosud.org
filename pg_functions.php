<?php 

function execute_sql($sql) {
    include 'config.php';  
    
    // Connexion à PostgreSQL
    $conn = pg_connect("dbname='" . $pg_bdd . "' user='" . $pg_lgn . "' password='" . $pg_pwd . "' host='" . $pg_host . "'");
    if (!$conn) {
        echo "Not connected";
        exit;
    };    

    // Execute query
    $res = pg_query($conn, $sql);
    if (!$res) {
        // echo pg_last_error($conn);
        echo "An SQL error occured.\n";
        exit;
    }

    
    
    // Pas result in an array
    $array_result = array();
    while ($row = pg_fetch_assoc( $res )) {
      $array_result[] = $row;
    } 

    // Returns JSON 
    header('Content-Type: application/json');
    echo json_encode($array_result, JSON_NUMERIC_CHECK);
    
};

function execute_sql_multi($sql_list) {
    
    include 'config.php'; 
    
    // Connexion à PostgreSQL
    $conn = pg_connect("dbname='" . $pg_bdd . "' user='" . $pg_lgn . "' password='" . $pg_pwd . "' host='" . $pg_host . "'");
    if (!$conn) {
        echo "Not connected";
        exit;
    };    
    
    // Run all queries
    $results = array();
    foreach ($sql_list as $sql) {

        // Execute query
        $res = pg_query($conn, $sql);
        if (!$res) {
            // echo pg_last_error($conn);
            echo "An SQL error occured.\n";
            exit;
        };

        // Pas result in an array
        $array_result = array();
        while ($row = pg_fetch_assoc( $res )) {
          $array_result[] = $row;
        };
        
        // Stock result
        array_push($results, $array_result);
        
    };


    // Returns JSON */
    header('Content-Type: application/json');
    echo json_encode($results, JSON_NUMERIC_CHECK);

};


function execute_sql_raw($sql) {
    include 'config.php';  
    
    // Connexion à PostgreSQL
    $conn = pg_connect("dbname='" . $pg_bdd . "' user='" . $pg_lgn . "' password='" . $pg_pwd . "' host='" . $pg_host . "'");
    if (!$conn) {
        echo "Not connected";
        exit;
    };    

    // Execute query
    $res = pg_query($conn, $sql);
    if (!$res) {
        // echo pg_last_error($conn);
        echo "An SQL error occured.\n";
        exit;
    }

	$a = "";
	while ($row = pg_fetch_row($res)) {
	  $a = $row[0];
	}    

    header('Content-Type: application/json');
	echo $a;
	
    // Pas result in an array
    // $array_result = array();
    // while ($row = pg_fetch_assoc( $res )) {
      // $array_result[] = $row;
    // } 

    // Returns JSON 
    // header('Content-Type: application/json');
    // echo json_encode($array_result, JSON_NUMERIC_CHECK);
    // echo $array_result;
    // echo json_encode($res);
    
};
?>