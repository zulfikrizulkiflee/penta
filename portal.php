<?php
//include stuff needed for session, database connection, and stuff
include('system_prerequisite.php');
?>

<?php include('header.php'); ?>

<div id="main-content" align="center" style="margin-top:<?php echo ($_SESSION['userID'] ? '80px' : '50px'); ?>;">
	<?php

	function displayPageUtama()
	{
		?>


<!--
		<table class="tableContent" style="width:290px; float:left; margin-top:10px;">
			<tr><th>Carian Pili</th></tr>
			<tr>
				<td style="height:152px; vertical-align:top">
					<div style="">
						Negeri:
						<select>
							<option>Johor</option>
							<option>Kedah</option>
							<option>Kelantan</option>
							<option>Melaka</option>
							<option>Negeri Sembilan</option>
							<option>Pahang</option>
							<option>Perak</option>
							<option>Perlis</option>
							<option>Pulau</option>
							<option>Sabah</option>
							<option>Sarawak</option>
							<option>Selangor</option>
							<option>Terengganu</option>
							<option>Wilayah Persekutuan Kuala Lumpur</option>
							<option>Wilayah Persekutuan Labuan</option>
							<option>Wilayah Persekutuan Putrajaya</option>
						</select>
						<br/><br/>
						Daerah:
						<select>
							<option>Petaling</option>
							<option>Hulu Langat</option>
							<option>Klang</option>
							<option>Gombak</option>
							<option>Kuala Langat</option>
							<option>Sepang</option>
							<option>Kuala Selangor</option>
							<option>Hulu Selangor</option>
							<option>Sabak Bernam</option>
						</select>
						<input class="red-button" type="button" value="CARI"/>
					</div>
				</td>
			</tr>
		</table>
--
		<?php
	}

	if($_GET['page']=='utama')
	{
		displayPageUtama();
	}
	elseif($_GET['page']=='mengenaikami')
	{
		?>
		<h1>Mengenai Kami</h1>
		<table class="tableContent" style="width:953px; float:left; margin-right:10px;">
			<tr>
				<th>Mengenai Kami</th>
			</tr>
			<tr>
				<td style="padding:15px;">
					<div style="text-align:center"><img src="images/kami_banner.jpg"><br/></div>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse quis nunc et nisi vulputate consequat in ut nibh. Pellentesque vel varius justo, sed vehicula libero. Vestibulum et vestibulum lacus, in ultricies tellus. Donec nisl erat, egestas sed nisl laoreet, lobortis aliquam libero. Curabitur scelerisque lorem nisl, vitae iaculis risus sollicitudin at. Nullam tempor iaculis eros vitae pharetra. Phasellus eu tincidunt eros. Quisque diam mi, tincidunt ut mauris malesuada, malesuada convallis turpis. Ut sit amet interdum ipsum. In facilisis, risus eget convallis dapibus, dolor enim dignissim urna, sit amet congue mauris ligula quis sem.</p>

					<p>Mauris et ante nibh. Pellentesque commodo placerat risus, in mattis odio euismod sit amet. Vestibulum porttitor, velit ac commodo porttitor, ante sapien porttitor odio, in viverra dui risus id sem. Quisque varius quam sit amet dui dictum lobortis. Duis laoreet tellus vel eros suscipit, at facilisis felis molestie. Suspendisse potenti. Nullam accumsan bibendum lacus, non sollicitudin elit lobortis a. Integer congue, augue id fringilla tincidunt, orci eros aliquam justo, sit amet semper ligula diam at orci. Proin in quam sed leo commodo mattis nec vitae velit.</p>

					<p>Donec tempor, justo at lobortis semper, lacus metus mattis orci, nec luctus tellus felis sit amet magna. Morbi mollis purus sed congue commodo. Suspendisse sollicitudin pharetra dignissim. Suspendisse auctor ultricies lectus at feugiat. Aliquam velit dolor, cursus vel pretium eu, faucibus vitae elit. Sed vestibulum eros vitae metus tempor, eget ullamcorper arcu dapibus. Fusce volutpat auctor tincidunt. In dignissim, felis nec suscipit tincidunt, sem nisl accumsan nisl, ut fringilla quam neque vitae ligula. Vivamus nec augue lorem. Praesent ac facilisis orci.</p>

					<p>In non mi aliquam, dignissim augue ut, rhoncus risus. Fusce suscipit velit ligula, vitae consequat nulla tristique quis. Proin volutpat laoreet dapibus. Suspendisse mollis mauris tortor, in commodo leo malesuada non. Pellentesque lacus quam, vulputate non purus vitae, convallis consequat erat. Phasellus iaculis orci malesuada, tempor erat non, condimentum enim. Vestibulum non molestie augue. Phasellus vel tellus magna. Fusce ligula sem, tincidunt at velit quis, pellentesque semper sapien. Nulla eleifend tempor pretium. Donec facilisis varius venenatis. Mauris blandit risus sit amet porta auctor.</p>

					<p>Ut faucibus pharetra lacus, sit amet malesuada elit ultrices posuere. Morbi in erat nibh. Integer vehicula dictum libero ultrices auctor. Proin consequat interdum mauris, quis ornare libero scelerisque vel. Mauris eleifend a sapien suscipit tristique. Duis non felis at tellus iaculis ultricies. Ut interdum ultricies neque a tristique.</p>
				</td>
			</tr>
		</table>
		<?php
	}
	elseif($_GET['page']=='latarbelakang')
	{
	?>
		<h1>Latar Belakang</h1>
		<table class="tableContent" style="width:953px; float:left; margin-right:10px;">
			<tr>
				<th>Latar Belakang</th>
			</tr>
			<tr>
				<td style="padding:15px;">
					<img src="images/bomba1.jpg" style="float:right; margin-left:10px; margin-bottom:10px;"/>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse quis nunc et nisi vulputate consequat in ut nibh. Pellentesque vel varius justo, sed vehicula libero. Vestibulum et vestibulum lacus, in ultricies tellus. Donec nisl erat, egestas sed nisl laoreet, lobortis aliquam libero. Curabitur scelerisque lorem nisl, vitae iaculis risus sollicitudin at. Nullam tempor iaculis eros vitae pharetra. Phasellus eu tincidunt eros. Quisque diam mi, tincidunt ut mauris malesuada, malesuada convallis turpis. Ut sit amet interdum ipsum. In facilisis, risus eget convallis dapibus, dolor enim dignissim urna, sit amet congue mauris ligula quis sem.</p>

					<p>Mauris et ante nibh. Pellentesque commodo placerat risus, in mattis odio euismod sit amet. Vestibulum porttitor, velit ac commodo porttitor, ante sapien porttitor odio, in viverra dui risus id sem. Quisque varius quam sit amet dui dictum lobortis. Duis laoreet tellus vel eros suscipit, at facilisis felis molestie. Suspendisse potenti. Nullam accumsan bibendum lacus, non sollicitudin elit lobortis a. Integer congue, augue id fringilla tincidunt, orci eros aliquam justo, sit amet semper ligula diam at orci. Proin in quam sed leo commodo mattis nec vitae velit.</p>

					<p>Donec tempor, justo at lobortis semper, lacus metus mattis orci, nec luctus tellus felis sit amet magna. Morbi mollis purus sed congue commodo. Suspendisse sollicitudin pharetra dignissim. Suspendisse auctor ultricies lectus at feugiat. Aliquam velit dolor, cursus vel pretium eu, faucibus vitae elit. Sed vestibulum eros vitae metus tempor, eget ullamcorper arcu dapibus. Fusce volutpat auctor tincidunt. In dignissim, felis nec suscipit tincidunt, sem nisl accumsan nisl, ut fringilla quam neque vitae ligula. Vivamus nec augue lorem. Praesent ac facilisis orci.</p>

					<p>In non mi aliquam, dignissim augue ut, rhoncus risus. Fusce suscipit velit ligula, vitae consequat nulla tristique quis. Proin volutpat laoreet dapibus. Suspendisse mollis mauris tortor, in commodo leo malesuada non. Pellentesque lacus quam, vulputate non purus vitae, convallis consequat erat. Phasellus iaculis orci malesuada, tempor erat non, condimentum enim. Vestibulum non molestie augue. Phasellus vel tellus magna. Fusce ligula sem, tincidunt at velit quis, pellentesque semper sapien. Nulla eleifend tempor pretium. Donec facilisis varius venenatis. Mauris blandit risus sit amet porta auctor.</p>

					<p>Ut faucibus pharetra lacus, sit amet malesuada elit ultrices posuere. Morbi in erat nibh. Integer vehicula dictum libero ultrices auctor. Proin consequat interdum mauris, quis ornare libero scelerisque vel. Mauris eleifend a sapien suscipit tristique. Duis non felis at tellus iaculis ultricies. Ut interdum ultricies neque a tristique.</p>
				</td>
			</tr>
		</table>
		<table class="tableContent" style="width:953px; float:left; margin-top:10px;">
			<tr>
				<th>Latar Belakang</th>
			</tr>
			<tr>
				<td style="padding:15px;">
					<img src="images/pili1.jpg" style="float:left; margin-right:10px; margin-bottom:10px;"/>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse quis nunc et nisi vulputate consequat in ut nibh. Pellentesque vel varius justo, sed vehicula libero. Vestibulum et vestibulum lacus, in ultricies tellus. Donec nisl erat, egestas sed nisl laoreet, lobortis aliquam libero. Curabitur scelerisque lorem nisl, vitae iaculis risus sollicitudin at. Nullam tempor iaculis eros vitae pharetra. Phasellus eu tincidunt eros. Quisque diam mi, tincidunt ut mauris malesuada, malesuada convallis turpis. Ut sit amet interdum ipsum. In facilisis, risus eget convallis dapibus, dolor enim dignissim urna, sit amet congue mauris ligula quis sem.</p>

					<p>Mauris et ante nibh. Pellentesque commodo placerat risus, in mattis odio euismod sit amet. Vestibulum porttitor, velit ac commodo porttitor, ante sapien porttitor odio, in viverra dui risus id sem. Quisque varius quam sit amet dui dictum lobortis. Duis laoreet tellus vel eros suscipit, at facilisis felis molestie. Suspendisse potenti. Nullam accumsan bibendum lacus, non sollicitudin elit lobortis a. Integer congue, augue id fringilla tincidunt, orci eros aliquam justo, sit amet semper ligula diam at orci. Proin in quam sed leo commodo mattis nec vitae velit.</p>

					<p>Donec tempor, justo at lobortis semper, lacus metus mattis orci, nec luctus tellus felis sit amet magna. Morbi mollis purus sed congue commodo. Suspendisse sollicitudin pharetra dignissim. Suspendisse auctor ultricies lectus at feugiat. Aliquam velit dolor, cursus vel pretium eu, faucibus vitae elit. Sed vestibulum eros vitae metus tempor, eget ullamcorper arcu dapibus. Fusce volutpat auctor tincidunt. In dignissim, felis nec suscipit tincidunt, sem nisl accumsan nisl, ut fringilla quam neque vitae ligula. Vivamus nec augue lorem. Praesent ac facilisis orci.</p>
				</td>
			</tr>
		</table>
		<?php
	}
	elseif($_GET['page']=='pilibomba')
	{
	?>
		<h1>Maklumat Pili Bomba</h1>
		<?php
		include('portal_pili.php');
	}
	elseif($_GET['page']=='aduan')
	{
	?>
		<h1>Aduan</h1>
		<?php
		include('portal_aduan.php');
	}
	elseif($_GET['page']=='hubungi')
	{
	?>
		<h1>Hubungi</h1>
		<?php
		include('portal_contact.php');
	}
	elseif($_GET['page']=='pilidtl')
	{
	?>
		<h1>Maklumat Pili Bomba</h1>
		<?php
		include('portal_pili_dtl.php');
	}
	else
	{
		displayPageUtama();
	}
0
	?>
</div>

<?php include('footer.php'); ?>