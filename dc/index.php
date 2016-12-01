<!DOCTYPE html> 
<html> 
<head> 
	<meta charset="UTF-8"> 
	<title>Home Page</title> 
	<link rel="stylesheet" href="style.css" type="text/css">
</head> 
<body> 
<?php
if(!empty($_GET["week"]) && isset($_GET["month"]) && isset($_GET["year"]) && $_GET["week"]>0 && $_GET["week"]<7 && $_GET["month"]>0 && $_GET["month"]<13 &&  $_GET["year"]>1970 && $_GET["year"]<2050){
	$month=$_GET["month"];
	$year=$_GET["year"];
	$weekstatic = $_GET["week"];
}
else{
	$day=date('d');
	$month=date('m');
	$year=date('Y');
}



$day_count = 1; // ������ ������ ���������� � 1.
$daysofmonth = date('t', mktime(0, 0, 0, $month, $day_count, $year)); // ������ ������� ���� � �������� ������
$dayofweek = date('w', mktime(0, 0, 0, $month, $day_count, $year)); // ������ ����� ���� ������ 1 ����� ��������� ������ � ���� �����: ����������� = 0, ����������� = 1 ..... ������� = 6.

//��� ��� ���������� � ������������ ����� ���������� � ����������� � ���������� � 0, �� ��������� ����������� � ����� ���� - ����� ����� ���� �������� � ��������� �����.
if($dayofweek == 0){ //���� ���� ������ ����� 0
	$dayofweek = 7;  // ����� ��������� ���� ������ � 7 - ��������� ��� � ��������: 1 - ����������� ..... 6 - �������.
}

if($dayofweek == 1){
	$first_day_calendar = date('d.m.Y', strtotime('monday', mktime(0, 0, 0, $month, $day_count, $year)));
}
else{
	$first_day_calendar = date('d.m.Y', strtotime('last monday', mktime(0, 0, 0, $month, $day_count, $year)));
}


$date_var = $first_day_calendar;
//������� ������� ��������� ����, ����� ��������� ��������� ������ ��� ������ ������ - ������ ������ (1-6), ������ ������ - ���� ������ (1-7).
$temp_day_count = 1; // ������� ��������� ���������� - ��� ����� �� 1 �� 42 (���������� ����� ������������) 6*7.
for($i=1;$i<=6;$i++){ // ���� ��� ��������� ������ �� 1 �� 6 (������������). (��� ��� ������ ������, ������� ����� ����������� ��� 6 ������)
	for($ii=1;$ii<=7;$ii++){ // ���� ��� ��������� ���� �� 1 �� 7(������������)
		$month_array[$i][$ii] = $date_var;
		if(!empty($day) && $day == substr($month_array[$i][$ii], 0, 2) && $month == substr($month_array[$i][$ii], 3, 2)){
			$weekstatic = $i;
		}
		$date_var = date('d.m.Y', strtotime($date_var.'+1 day'));
		
	}
}



$nextyear=$prevyear=$year;
if(($month+1)>=13){
	$nextmonth=1;
	$nextyear=$year+1;
}
else{
	$nextmonth=$month+1;
}

if(($month-1)<=0){
	$prevmonth=12;
	$prevyear=$year-1;
}
else{
	$prevmonth=$month-1;
}


echo "<table border=1>";
echo "<tr><td><a href='?week=1&month=". $prevmonth ."&year=". $prevyear ."'><</a></td><td  colspan='5'>";
echo date('F.Y', strtotime('last monday', mktime(0, 0, 0, substr($month_array[2][1], 3, 2), substr($month_array[2][1], 0, 2), substr($month_array[2][1], 6, 4) )));
echo "</td><td><a href='?week=1&month=". $nextmonth ."&year=". $nextyear ."'>></a></td></tr>";
echo "<tr><th>E</th><th>T</th><th>K</th><th>N</th><th>R</th><th>L</th><th>P</th></tr>";
for($a=1;$a<=6;$a++){
	if($a==$weekstatic){
		echo "<tr class='colorweek'>";
	}
	else{
		if(substr($month_array[$a][1], 3, 2)>$month && substr($month_array[$a][1], 6, 4)>=$year){
			echo "<tr class='hoverweek' onclick='location.href=\"?week=1&month=". ($month+1) ."&year=". $year ."\"'>";
		}else{
			echo "<tr class='hoverweek' onclick='location.href=\"?week=". $a ."&month=". $month ."&year=". $year ."\"'>";
		}
	}
	for($b=1;$b<=7;$b++){
		if(substr($month_array[$a][$b], 3, 2)<>$month)
			echo "<td style='color:#DDDDDD'>";
		else
			echo "<td>";

			echo substr($month_array[$a][$b], 0, 2);

		echo "</td>";
	}
	echo "</tr>";
}
echo "</table>";

$time=8;
if(!empty($_SESSION['user']) && !empty($_SESSION['status'])){

#foreach($_POST as $i => $value) {
#echo $i."-".$_POST[$i];}

echo '<form action="index.php" method="POST" name="table">';
echo "<table border=1>";
echo "<tr><th colspan='8'>".$month_array[$weekstatic][1]." - ".$month_array[$weekstatic][7]."</th></tr>";
for($m=0;$m<=12;$m++){
	echo "<tr>";
	for($g=0;$g<=7;$g++){
		if($m==0 && $g==0)
			echo "<td></td>";
		if($m==0 && $g>0)
			echo "<td>". $month_array[$weekstatic][$g] ."</td>";
		if($g==0 && $m>0){
			echo "<td>".$time."-00</td>";
			$time++;
	
		}
		if($g>0 && $m>0){
		
		echo '<td><input type="text" name="'.substr($month_array[$weekstatic][$g], 6, 4).'-'.substr($month_array[$weekstatic][$g], 3, 2).'-'.substr($month_array[$weekstatic][$g], 0, 2).' '.($time-1).':00:00"></td>';

			}
			

	}
	echo "</tr>";
}
echo "</table>";
echo	'<input type="submit" name="button" value="OK">';
echo "</form>";
}

else{
echo "<table border=1>";
echo "<tr><th colspan='8'>".$month_array[$weekstatic][1]." - ".$month_array[$weekstatic][7]."</th></tr>";
for($m=0;$m<=12;$m++){
	echo "<tr>";
	for($g=0;$g<=7;$g++){
		if($m==0 && $g==0)
			echo "<td></td>";
		if($m==0 && $g>0)
			echo "<td>". $month_array[$weekstatic][$g] ."</td>";
		if($g==0 && $m>0){
			echo "<td>".$time."-00</td>";
			$time++;
		}
	}
	echo "</tr>";
}
echo "</table>";
}

?>
</body> 
</html>