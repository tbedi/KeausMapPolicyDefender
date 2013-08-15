<?php 

class Grid  {
	
	public $_html="";
	
	protected $_name="";
	protected $_export_enabled=0;
	protected $_columns=array();
	
	protected $_data;
	
	public function NewGrid(array $grid){
		$this->SetName($grid['name']);
		if (isset($grid['export_enabled']))
			$this->SetExportEnabled($grid['export_enabled']);
		  
			
		$this->PrepareGridHtml($grid);
	}
	
	public function SetName($name){
		$this->_name=$name;
	}
	public function SetExportEnabled($enabled){
		$this->_export_enabled=$enabled;
	}
	
	
	public function PrepareGridHtml(array $grid){
		
		$this->_html="";
		
		if (isset($grid['class']) && $grid['class'])
			$class_string=' class="'.$grid['class'].' table-'.$this->_name.'" ';
		else 
			$class_string="table-'.$this->_name.'";
		
		if (isset($grid['id']) && $grid['id'])
			$id_string=' id="'.$grid['id'].'" ';
		else
			$id_string="";			

		if (isset($grid['align']) && $grid['align'])
			$align_string=' align="'.$grid['align'].'" ';
		else
			$align_string="";
		
		$this->_html='<table '.$class_string.$id_string.$align_string.' >';
		$this->_html.='<tbody id="data">';
	}
	
	public function AddColumn(array $column){
		
		if (!isset($column['order']) || !$column['order']) {
			$column['order']=count($this->_columns);
		}
		
		array_push($this->_columns,$column);
	 
 
	}
	
	protected function sortColumns(){
		$tmp = Array();
		foreach($this->_columns as &$ma)
			$tmp[] = &$ma["order"];
		
		array_multisort($tmp,$this->_columns, SORT_ASC,SORT_NUMERIC);
	}
	/*
	 $columns['sortable'];
	$columns['search'];
	$columns['title'];
	$columns['key'];
	$columns['order'];
	*/
	
	public function RenderColumns(){
		$this->sortColumns();
		$cols_html="<tr>";
		$i=1;
		/*Checbox for export*/
			if ($this->_export_enabled==1){
				$cols_html.='<td class="columnt-title title-checkall"><a href="javascript:void(0);" onclick="check_all();">Select All</a>';
				$cols_html.='</td>';
			}
		/*Checbox for export*/
		foreach ($this->_columns as $col){
			$cols_html.='<td class="columnt-title title-'.$col['key'].'">';
			
			 
			
			if (isset($col['type']) && $col['type']=='url' ) {
				
			}
			
			/*Sorting 1st step*/
			if (isset($col['sortable']) && $col['sortable'] ) {
				if (isset($col['sorted']) && $col['sorted'] ) {
					$sorted=$col['sorted']; //$sorted asc/desc
					$arrow_html='<img class="direction-arrow arrow-'.$sorted.'"  style="float:right;" width="22"  src="images/arrow_'.$sorted.'_1.png" />';		
					$new_sort=($sorted=="asc" ? "desc": "asc");
				}else {
					$new_sort="asc";
					$arrow_html="";
				}
				$cols_html.='<a href="javascript:void(0);" onclick="sort_grid(\''.$col['key'].'\',  \''.$new_sort.'\',\''.$this->_name.'\' );"  >'.$col['title'].$arrow_html.'</a>' ;
				$sorting=1;
			} else {
				$cols_html.=$col['title'];
			}
			
			/*Search 2nd step*/
			if (isset($col['search']) && $col['search'] ) {
				if(isset($col['search_placeholder']) && $col['search_placeholder'])
					$placeholder=$col['search_placeholder'];
				else 
					$placeholder="";
				if(isset($col['search_value']) && $col['search_value'])
					$search_val=$col['search_value'];
				else 
					$search_val="";
				
				$cols_html.='<p><input type="text" id="textBoxSearch2 '.$this->_name.'_'.$col['key'].'_search" value="'.$search_val.'" maxlength="1000" size="30" placeholder="'.$placeholder.'" class="searchh '.$this->_name.'-'.$col['key'].'-search"></p>';
				$search=1;
			}
			
			/*Set column html*/
			if (!$sorting && !$search)
				$cols_html.=$col['title'];
			 
			
			$cols_html.='</td>';
			$i++;
		}		
		$cols_html.="</tr>";
		$this->_html.=$cols_html;
		 
	}
	
	public function  setGridData($data) {
		$this->_data=$data;
	}
	
	public function RenderRows(){
		$rows_html="";
		$i=1;
		foreach ($this->_data as $row){
			$row_html="<tr>";
			if ($this->_export_enabled==1){
				$row_html.='<td><input type="checkbox" name="row[]"   />';
			}
			foreach ($this->_columns as $column) {
				 
				if (isset($column['type']) && $column['type']=="url") { //URL TYPE
					$row_html.='<td><a href="'.$row->$column['key'].'" target="_blank">'.$column['url_label'].'</a></td>';
				} else if (isset($column['type']) && $column['type']=="price"){  //PRICE TYPE			 
					$row_html.='<td>'.$this->toMoney($row->$column['key']).'</td>';
				} else if (isset($column['type']) && $column['type']=="violation"){ //VIOLATION TYPE
						$cell_id="vio";
						if ($row->$column['key'] >= 5 && $row->$column['key'] <10 ) {
							$cell_id="vioO"; //Condition for colors
						} else if ($row->$column['key'] >=10) {
							$cell_id="vioR";
						}
						$row_html.='<td id="'.$cell_id.'" >'.$this->toMoney($row->$column['key']).'</td>';
				}else if (isset($column['type']) && $column['type']=="product"){ //PRODUCT TYPE
					$row_html.='<td><a href="/index.php?tab=violations-history&action=search&field=sku&value='.$row->$column['key'].'">'.$row->$column['key'].'</a></td>';
				}else if (isset($column['type']) && $column['type']=="dealer"){ //DEALER TYPE
					$row_html.='<td><a href="/index.php?&tab=violations-history&action=search2&flag=1&field=name&value='.$row->$column['key'].'">'.$row->$column['key'].'<a/></td>';
				}else { //DEFAULT TYPE
					$row_html.='<td>'.$row->$column['key'].'</td>';
				}
			}
			 
			
			$row_html.="<tr>";
			$rows_html.=$row_html;
		}
		
		$this->_html.=$rows_html;
	}
	
	public function prepareGridHtmlFinal(){
		$this->_html.='</tbody>';
		$this->_html.='</table>';		 
	}
	
	public function getHTML(){
		$this->RenderColumns();
		$this->RenderRows();
		
		
		$this->prepareGridHtmlFinal();		
		return $this->_html;		
	}
	
	public function  toMoney($val,$symbol='$',$decimal=2)
{


    $no = $val; 
    $c = is_float($no) ? 1 : number_format($no,$decimal);
    $d = '.';
    $t = ',';
    $sign = ($no < 0) ? '-' : '';
    $i = $no=number_format(abs($no),$decimal); 
    $j = (($j = count($i)) > 3) ? $j % 3 : 0; 

   return  $symbol.$sign .($j ? substr($i,0, $j) + $t : '').preg_replace('/(\d{3})(?=\d)/',"$1" + $t,substr($i,$j)) ;

}
}