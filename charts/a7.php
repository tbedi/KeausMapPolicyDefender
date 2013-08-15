 <?php
// select default
$sortDefault = 'id';

// select array
$sortColumns = array('id','lname','company','description','keywords','type_name','street_address','city','state','country','phone','email','website','contact_email','contact_name');

// define sortable query ASC DESC
$sort = (isset($_GET['sort'])) && in_array($_GET['sort'], $sortColumns) ? $_GET['sort']: $sortDefault;
$order = (isset($_GET['order']) && strcasecmp($_GET['order'], 'DESC') == 0) ? 'DESC' : 'ASC'; 

$result = mysql_query("SELECT * FROM categories ORDER BY $order"); 

// clickable header
?>
<table width='350' border='1'> 
<tr> 
<td><a href='?sort=id&order=" . (<?php echo $order == 'DESC' ? 'ASC' : 'DESC' ?>) . "'>id<?
if($_GET["order"]=="ASC" && $_GET["sort"]=="id"){
echo '<IMG id="IMG0" name="IMG0" src="images/1.png" width="8px" height="8px">'; 
}
if($_GET["order"]=="DESC" && $_GET["sort"]=="id"){
echo '<IMG id="IMG0" name="IMG0" src="images/2.png" width="8px" height="8px">'; 
}
?></a></td> 
<td><a href='?sort=name&order=" . (<?php echo $order == 'DESC' ? 'ASC' : 'DESC' ?>) . "'>Name<?
if($_GET["order"]=="ASC" && $_GET["sort"]=="name"){
echo '<IMG id="IMG0" name="IMG0" src="images/1.png" width="8px" height="8px">'; 
}
if($_GET["order"]=="DESC" && $_GET["sort"]=="id"){
echo '<IMG id="IMG0" name="IMG0" src="images/2.png" width="8px" height="8px">'; 
}
?></a></td> 
</tr>
<?php
// list records 
while ($row = mysql_fetch_assoc($result)) { 
echo "<tr> 
<td>$row[CostCode]</td> 
<td>$row[CostItem]</td> 
</tr>"; 
}
echo "</table>";
?>