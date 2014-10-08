<? if($query->num_rows() > 0)
		{
		//$json_array = {};
foreach($query->result() as $row):
	$json_array[$row->id] = $row->arquivo;
	//img("uploads/".$row->arquivo);
	//anchor('admin/remove/'.$row->id, 'X');
endforeach; 
echo(json_encode($json_array));
} ?>