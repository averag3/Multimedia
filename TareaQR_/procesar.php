<?php 

function guardarFormularioAlumno($fname, $sname, $fape, $sape, $address, $email, $rut, $digver){

	require 'connection.php';
	$sql = ("INSERT INTO alumno(fname, sname, fape, sape, address, email, rut, digver) VALUES (:fname, :sname, :fape, :sape, :address, :email, :rut, :digver)");
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':fname', $fname);
	$stmt->bindParam(':sname', $sname);
	$stmt->bindParam(':fape', $fape);
	$stmt->bindParam(':sape', $sape);
	$stmt->bindParam(':address', $address);
	$stmt->bindParam(':email', $email);
	$stmt->bindParam(':rut', $rut);
	$stmt->bindParam(':digver', $digver);
	
	if(!$stmt){
		echo "Error";
		$conn = null;
	}
	
	else {
		$stmt->execute();
		echo "Se inserto la weaita";
		$conn = null;
	}
}

function crearPDF($rut, $digver){
	require 'connection.php';
	require_once('tcpdf/tcpdf.php');
 	
 	$sql = ("SELECT fname, sname, fape, sape, address, email, rut, digver FROM alumno WHERE rut = '$rut' AND digver = '$digver'");
	$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
 
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Miguel Caro');
	$pdf->SetTitle('DATOS DEL ALUMNO');
 
	$pdf->setPrintHeader(false); 
	$pdf->setPrintFooter(false);
	$pdf->SetMargins(20, 20, 20, false); 
	$pdf->SetAutoPageBreak(true, 20); 
	$pdf->SetFont('Helvetica', '', 10);
	$pdf->addPage();
 
	$content = '';
 
	$content .= '
		<div class="row">
        	<div class="col-md-12">
            	<h1 style="text-align:center;">'.'Datos del Alumno'.'</h1>
 
      <table border="1" cellpadding="5">
        <thead>
          <tr>
            <th>DNI</th>
            <th>A. PATERNO</th>
            <th>A. MATERNO</th>
            <th>NOMBRES</th>
            <th>DIRECCION</th>
            <th>EMAIL</th>
          </tr>
        </thead>
	';
 	
	foreach ($conn->query($sql) as $row) {
	$content .= '
		<tr bgcolor="white">
            <td>'.$row['rut'].' - '.$row['digver'].'</td>
            <td>'.$row['fape'].'</td>
            <td>'.$row['sape'].'</td>
            <td>'.$row['fname'].' '.$row['sname'].'</td>
            <td>'.$row['address'].'</td>
            <td>'.$row['email'].'</td>
        </tr>
	';
	}
 
	$content .= '</table>';
 
	$content .= '
		<div class="row padding">
        	<div class="col-md-12" style="text-align:center;">
            	<span>Pdf Creator </span><a href="http://www.redecodifica.com">By Miguel Angel</a>
            </div>
        </div>
 
	';
 
	$pdf->writeHTML($content, true, 0, true, 0);
 
	$pdf->lastPage();
	$pdf->output('Reporte.pdf', 'I');
}

if(isset($_REQUEST['alumno_form'])){
	$fname = $_REQUEST['fname'];
	$sname = $_REQUEST['sname'];
	$fape = $_REQUEST['fape'];
	$sape = $_REQUEST['sape'];
	$address = $_REQUEST['address'];
	$email = $_REQUEST['email'];
	$rut = $_REQUEST['rut'];
	$digver = $_REQUEST['digver'];
	guardarFormularioAlumno($fname, $sname, $fape, $sape, $address, $email, $rut, $digver);
}

if(isset($_REQUEST['crear_pdf'])){
	$rut = $_REQUEST['rut'];
	$digver = $_REQUEST['digver'];
	crearPDF($rut, $digver);
}

?>