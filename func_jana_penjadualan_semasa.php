<?php
//include('system_prerequisite.php');	

//required files
require_once('conf.php');                                                //falcon configuration file
require_once('class/db_'.DBMS_NAME.'.php');                //database class
require_once('class/sql_'.DBMS_NAME.'.php');        //sql class
require_once('class/dal.php');                                        //bl class
require_once('func_sys.php');                                        //system function
require_once('func_common.php');                                //common function

//create database connection
$myDbConn = new dbConnection;                                                                                        //create db connection object
$myDbConn->init(DB_USERNAME,DB_PASSWORD,DB_DATABASE,DB_CONNECTION);
$dbc = $myDbConn->getConnString();                                                                                //get connection string

//create database query object
$myQuery = new dbQuery($dbc);

//create database sql object
$mySQL = new dbSQL($dbc);
$dal = new DAL($dbc);





	// Select function fn_jana_penjadualan()
	$qFunc = "select epili_usr.fn_jana_penjadualan_semasa() from dual";
	$getFunc = $myQuery->query($qFunc, 'SELECT', 'NAME');
	



	$close_db = $myDbConn->disconnect();

?>



