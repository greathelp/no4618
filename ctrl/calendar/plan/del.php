<?php

include_once '../../db.php';

$gid = filter_input( INPUT_GET , "gid" );
$id = filter_input( INPUT_GET , "id" );
$date = filter_input( INPUT_GET , "date" );

$sql = "delete from " . TBL_PLAN . " where id = " . $id;

$conn = DB_Conn();

$rslt = $conn->prepare( $sql );

$rslt->execute();

header( "Location: plan_edit.php?gid=" . $id . "&date=" . $date );
