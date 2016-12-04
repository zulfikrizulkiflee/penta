<?php
//report configuration
require_once('../system_prerequisite.php');

//validate that user have session
validateUserSession('../index.php');

function get_string_between($string, $start, $end) 
{
    preg_match_all( '/' . preg_quote( $start, '/') . '(.*?)' . preg_quote( $end, '/') . '/', $string, $matches);
    return $matches[1];
}

$request_url=$_SERVER['REQUEST_URI'];

$tempParam_1 = get_string_between($request_url, 'report.php?', '.prpt/viewer?’);
$param_1 = implode(',',$tempParam_1 );

$tempParam_2 = get_string_between($request_url, ‘.prpt/viewer?’, '&close');
$param_2 = '&'.implode(',',$tempParam_2);

$tempParam_2_1 = get_string_between($request_url, ‘.prpt/viewer?’, 'close');
$param_2_1 = '&'.implode(',',$tempParam_2_1);

$tempParam_3 = get_string_between($param_2, '&', '=');
$tempParam_4 = get_string_between($param_2_1, '=', '&');

?>

<form name="form1" method="POST" action="<?php echo LAPORAN;?>/pentaho/api/repos/<?php echo $param_1.’.prpt/viewer?’;?>”>
<input name="id" type="hidden" value="<?php echo USERLAP;?>" />
<input name="passwd" type="hidden" value="<?php echo PASSLAP;?>" />
<?php
for($a=0; $a<count($tempParam_3); $a++){
echo '<input name='.'"'.$tempParam_3[$a].'" type='.'"hidden" value='.'"'.$tempParam_4[$a].'" >';
}
?>

<script>document.form1.submit();</script>
</form>





