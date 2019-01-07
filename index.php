	<?php   
		include("includes/header.php");
	?>
	<?php   
		include("library/config.php");
		include("library/database.php");

		$database = new Database();
	?>
<html>
	<div class="row mt-3 ml-1">
		<!-- Ana e majt -->
		<div class="col-md-8 phpcoding">
			<section class="headeroption">
				<h2>Uploading Image File with PHP</h2>
			</section>
			<section class="mainoption">
				<div class="myform">

		<?php     
							//if($_SERVER['REQUEST_METHOD'] == 'POST') and if(isset($_POST['submit'])) - any derivatives of if(isset($_POST[...])) work differently, but have the same goals in mind. They both are used to check if form submissions were correctly done. One of those are wrong because it's a "hack" way of checking for form submissions.
							
							//if(isset($_POST[...])) is mostly used by beginner PHP programmers. If they are matured and have some kind of PHP knowledge, but still use if(isset($_POST[...])), then they are the ones keeping the legacy going.

							//if($_SERVER['REQUEST_METHOD'] == 'POST') is actually the correct way of checking for form submissions.
						if($_SERVER["REQUEST_METHOD"] == "POST"){
							$permited = array('jpg','jpeg','png','gif');
							echo	$file_name = $_FILES['image']['name'];
							$file_name = $_FILES['image']['name'];
							echo "<br/>";
							echo	$file_size = $_FILES['image']['size'];
							echo "<br/>";
							echo	$file_tmp_name = $_FILES['image']['tmp_name'];
							echo "<br/>";
								//explode — Split a string by a string.Ndan emrin
							$div = explode('.', $file_name);
									//print_r($div);//Kjo afishon:( [0] => CV-A4 [1] =>png): 1 tregon qe kjo ka 1 emer e jo emri.emri2.emri3
									// $file_ext = end($div); //Afishohet tipi i image: jpg.
							$file_ext = strtolower(end($div));	//Afishon: jpg
							echo "<br/>";
								//substr — Return part of a string. 
								//substr(EKodojm md5(time()), prej0fillon, 10shifra)
							$unique_image = substr(md5(time()), 0, 10).'.'.$file_ext;//Afishon: e55cfb9301.png(varet nga tipi i imazhit)
							$uploaded_image = "uploads/".$unique_image;
							//Fajlli s'duhet te jete i zbrazet:
							if (empty($file_name)) {
								echo "<span class='error'>Please Select One Image.</span>";
							}elseif ($file_size > 1073741824) {
								//Ketu caktojm madhesin e fajllit:
								echo "<span class='error'>Image Size should be less then 1GB.</span>";
							}elseif (in_array($file_ext, $permited)=== false) {
								//in_array — Checks if a value exists in an array
								//Kontrollojm se tipi i fajllit a pershtatet me tipet e caktuara te imazheve:$permited
								echo "<span class='error'>You can upload only:-".implode(',',$permited).".</span>";
							}else{
										// $folder = "uploads/";

										//move_uploaded_file — Moves an uploaded file to a new location
										//move_uploaded_file ( string $filename , string $destination )
										// move_uploaded_file(filename,destination);
										// move_uploaded_file($file_tmp_name,$folder.$file_name);
								move_uploaded_file($file_tmp_name,$uploaded_image);
								$query = "INSERT INTO tbl_image(image) VALUES('$uploaded_image')";
								$inserted_rows = $database->insert($query);

								if($inserted_rows){
									echo "<span class='success'>Image Inserted Successfully.</span>";
								}else{
									echo "<span class='error'>Image Not Inserted !</span>";
								}
							}
						}
		?>
					<form action="" method="post" enctype="multipart/form-data">
						<table class="table p-5">
<!-- 							<thead>
							    <tr>
							      <th scope="col">#</th>
							      <th scope="col">First</th>
							      <th scope="col">Last</th>
							      <th scope="col">Handle</th>
							    </tr>
							</thead> -->
							<tbody>
<!-- 							    <tr>
							        <th scope="row">1</th>
							        <td>Text</td>
							        <td>Otto</td>
							        <td>@mdo</td>
							    </tr> -->
							    <tr>
							        <td>Select Image</td>
							        <td>
							        	<input type="file" name="image" />
							        </td>
							    </tr>
							    <tr>
							        <td>Submit Image</td>
							        <td>
							        	<input type="submit" name="submit" value="Upload" />
							        </td>
							    </tr>
							</tbody>
						</table>
					</form>
					<table class="table p-5">
 							<!--<thead class="row"> -->
							    <tr>
							      <th>ID</th>
							      <th>IMAGE</th>
							      <th>ACTION</th>
							    </tr>
							<!-- </thead> -->
			<?php
							if (isset($_GET['del'])) {
								$id = $_GET['del'];
								//Fshirja e imazheve ne follder baz te id-se:	
								$getquery = "SELECT * FROM `tbl_image` WHERE id='$id' ";
								$getImg = $database->select($getquery);
								if ($getImg) {
											//mysqli_result::fetch_assoc
											//mysqli_result::fetch_assoc -- mysqli_fetch_assoc — 
											//fetch_assoc()->Fetch a result row as an associative array
									while ($imgdata = $getImg->fetch_assoc()) {
										$delimg = $imgdata['image'];
											//unlink — Deletes a file
										unlink($delimg);
									}
								}
								//Fshirja e imazheve ne database ne baz te id-se:		
								$query = "DELETE FROM tbl_image WHERE id='$id' ";
								$delImage = $database->delete($query);
								if($delImage){
									echo "<span class='success'>Image Deletted Successfully.</span>";
								}else{
									echo "<span class='error'>Image Not Deletted !</span>";
								}
							}
			?>
			<?php   
										// $query = "SELECT * FROM `tbl_image` ORDER BY `id` DESC LIMIT 1";
							$query = "SELECT * FROM `tbl_image`";
							$getImage = $database->select($query);
							if ($getImage) {
								$i = 0;
								while ($result = $getImage->fetch_assoc()) {
									$i++;
			?>						
							<!-- <tbody> -->
							    <tr>
									<!-- <th scope="row">*</th> -->
							        <td>
							        	<!-- Shfaqja e ID se fotos -->
							        	<?php   echo $i; ?>
							        </td>
							        <td>
							        	<!-- Shfaqja e Imazhit -->
							        	<img src="<?php echo $result['image']; ?>" class="image_table">
							        </td>
							        <td>
							        	<a href="?del=<?php echo $result['id']; ?>" class="w-50 h-50 mt-5 mr-5 mb-5">DELETE</a>
							        </td>
			<?php
							        			// echo "<br/>";
											}
										}
			?>
							    </tr>
							<!-- </tbody> -->
					</table>
				</div>
			</section>
		</div>
		<!-- Ana e djathet -->
		<div class="col-md-4 float-right">
			<?php  include("includes/sidebar.php");?>
		</div>
	</div>
	<!-- Footer -->
	<?php  include("includes/footer.php");?>
	<script type="text/javascript" src="js/bootstrapjquery.js"></script>
	<script type="text/javascript" src="js/popper.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
</body>
</html>
