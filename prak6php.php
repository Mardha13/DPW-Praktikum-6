<?php
echo $action = $_REQUEST['action'];

parse_str($_REQUEST['dataku'], $hasil);
echo "Nama Depan: ".$hasil['namaDepan']."<br/>";
echo "Nama Belakang: ".$hasil['namaBelakang']."<br/>";

$gambar1 = $_FILES["gambar2"];

/* Error File Handling */
$username = trim($hasil['username']);
if (!empty($gambar1["name"]) and !empty($username)){
	$namafile = $gambar1["name"];
	preg_match("/([^\.]+$)/", $namafile, $ext);
	$file_ext = strtolower($ext[1]);
	$namafilebaru = $hasil['username'].".".$ext[1];
    $file = $gambar1["tmp_name"];
	$extensions= array("jpeg","jpg","png");
	$errors = array();
	if(in_array($file_ext,$extensions) === false)
	 $errors[] = "Extensi yang diperbolehkan jpeg atau png.";
	
	//Kirim pesan error jika ukuran file > 500kB
	$file_size = $gambar1['size'];
	if($file_size > 2097152)
	 $errors[] = "Ukuran file harus lebih kecil dari 2MB.";
    
	//Upload file
	if(empty($errors)){
		if(move_uploaded_file($file, "uploads/" . $namafilebaru))
			echo "Uploaded dengan nama $namafilebaru";
	}
}else echo $errors[] = "Lengkapi username dan gambarnya. ";
echo "<br/>";

if(!empty($errors)){
	echo "Error : ";
	foreach ($errors as $val)
		echo $val;
}

if(empty($errors)){
if($action == 'create')
	$syntaxsql = "insert into tab_form values ('$hasil[firstname]','$hasil[lastname]', '$hasil[username]', '$hasil[email]', '$hasil[address]', '$hasil[country]', '$hasil[state]', '$hasil[zip]', '$hasil[paymentMethod]', '$hasil[CCname]', '$hasil[CCnumber]', '$hasil[CCexpiration]', '$hasil[CCcvv]', now(), $namafilebaru))";
elseif($action == 'update')
	$syntaxsql = "update tab_form set firstname = '$hasil[firstName]', lastname = '$hasil[lastName]'), username = '$hasil[username]', email = '$hasil[email]', address = '$hasil[address]', country = '$hasil[country]', state = '$hasil[state]', zip = '$hasil[zip]', paymentMethod = '$hasil[paymentMethod]', CCname = '$hasil[CCname]', CCnumber = '$hasil[CCnumber]', CCexpiration = '$hasil[CCexpiration]', CCcvv = '$hasil[CCcvv], foto = '$namafilebaru' where username = '$hasil[username]'";
elseif($action == 'delete')
	$syntaxsql = "delete from tab_form where username = '$hasil[username]'";
elseif($action == 'read')
	$syntaxsql = "select * from tab_form";

//eksekusi syntaxsql 
$db_hostname = 'localhost'; 
$db_username = 'root'; 
$db_password = ''; 
$db_name = 'dpw';

$conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name); 
if ($conn->connect_errno) {
  echo "Failed to connect to MySQL: " . $conn -> connect_error;
  exit();
}else{
  echo "Database connected. ";
}

if ($conn->query($syntaxsql) === TRUE) {
	echo "Query $action with syntax $syntaxsql suceeded !";
}
elseif ($conn->query($syntaxsql) === FALSE){
	echo "Error: $syntaxsql" .$conn->error;
}

else{
	$result = $conn->query($syntaxsql);
	if($result->num_rows > 0){
		echo "<table id='tresult' class='table table-striped table-bordered'>";
		echo "
		<thead>
		<th>First_Name</th>
		<th>Last_Name</th>
		<th>Username</th>
		<th>Email</th>
		<th>Address</th>
		<th>Country</th>
		<th>State</th> 
		<th>ZIP</th>
		<th>Payment_Menthod</th>
		<th>CC_Name</th>
		<th>CC_Number</th>
		<th>CC_Expirration</th>
		<th>CC_CVV</th>
		<th>foto</th> where username = '$hasil[username]'
		</thead>";
		echo "<tbody>";
		while($row = $result->fetch_assoc()) {
			echo "
			<tr>
			<td>". $row['First_Name']."</td>
			<td>". $row['Last_Name']."</td>
			<td>". $row['Username']."</td>
			<td>". $row['Email']."</td>
			<td>". $row['Address']."</td>
			<td>". $row['Country']."</td>
			<td>". $row['State']."</td>
			<td>". $row['ZIP']."</td>
			<td>". $row['Payment_Menthod']."</td>
			<td>". $row['CC_Name']."</td>
			<td>". $row['CC_Number']."</td>
			<td>". $row['CC_Expirration']."</td>
			<td>". $row['CC_CVV']."</td>
			<td>". $row['foto']."</td>
			</tr>";
		}
		echo "</tbody>";
		echo "</table>";
	}
}
$conn->close();
}

?>