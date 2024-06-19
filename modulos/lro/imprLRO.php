<?php
session_start();
require_once(dirname(dirname(__FILE__))."/config/abrealas.php");
if(!isset($_SESSION['AdmUsu'])){
    echo " A sessão foi encerrada.";
    return false;
 }

 date_default_timezone_set('America/Sao_Paulo');
 if(isset($_REQUEST["acao"])){
    $Acao = $_REQUEST["acao"];
    require_once('../../class/fpdf/fpdf.php'); // adaptado ao PHP 7.2 - 8.2
    define('FPDF_FONTPATH', '../../class/fpdf/font/');  
    $Dom = "logo_comunhao_completa_cor_pos_150px.png";

    $rsCabec = pg_query($Conec, "SELECT cabec1, cabec2, cabec3 FROM ".$xProj.".setores WHERE codset = ".$_SESSION["CodSetorUsu"]." ");
    $rowCabec = pg_num_rows($rsCabec);
    $tblCabec = pg_fetch_row($rsCabec);
    $Cabec1 = $tblCabec[0];
    $Cabec2 = $tblCabec[1];
    $Cabec3 = $tblCabec[2];

    class PDF extends FPDF{
        function Footer(){
           // Vai para 1.5 cm da parte inferior
           $this->SetY(-15);
           // Seleciona a fonte Arial itálico 8
           $this->SetFont('Arial','I',8);
           // Imprime o número da página corrente e o total de páginas
           $this->Cell(0, 10, 'Impresso: '.date("d/m/Y H:i").'                   Pag '.$this->PageNo().'/{nb}', 0, 0, 'R');
         }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages(); // pega o número total de páginas
    $pdf->AddPage();
    //Monta o arquivo pdf        
    $pdf->SetFont('Arial', '' , 12); 
    $pdf->SetTitle('LRO', $isUTF8=TRUE);
    $pdf->SetDrawColor(200); // cinza claro

    if($Dom != "" && $Dom != "NULL"){
        if(file_exists('../../imagens/'.$Dom)){
            if(getimagesize('../../imagens/'.$Dom)!=0){
                $pdf->Image('../../imagens/'.$Dom,12,8,16,20);
            }
        }
    }
    $pdf->SetX(40); 
    $pdf->SetFont('Arial','' , 14); 
    $pdf->Cell(150, 5, $Cabec1, 0, 2, 'C');
    $pdf->SetFont('Arial','' , 12); 
    $pdf->Cell(150, 5, $Cabec2, 0, 2, 'C');
    $pdf->SetFont('Arial','' , 10); 
    $pdf->Cell(150, 5, $Cabec3, 0, 2, 'C');
    $pdf->SetFont('Arial', '' , 10);
    $pdf->SetTextColor(25, 25, 112);
    if($Acao == "impr" ){
        $pdf->MultiCell(150, 3, "Livro de Registro de Ocorrências", 0, 'C', false);
    }

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 6);
    $pdf->ln();
    $lin = $pdf->GetY();
    $pdf->Line(10, $lin, 200, $lin);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->ln(7);

    $rs = pg_query($Conec, "SELECT ".$xProj.".livroreg.id, numrelato, to_char(".$xProj.".livroreg.datains, 'DD/MM/YYYY'), turno, descturno, nomecompl, usuant, relato, ocor 
    FROM ".$xProj.".livroreg INNER JOIN ".$xProj.".poslog ON ".$xProj.".livroreg.codusu = ".$xProj.".poslog.pessoas_id
    WHERE ".$xProj.".livroreg.ativo = 1 ORDER BY ".$xProj.".livroreg.datains DESC ");
    $row = pg_num_rows($rs);

    if($row > 0){
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->SetX(15);
        $pdf->Cell(20, 4, "Número", 0, 0, 'L');
        $pdf->Cell(20, 4, "Data", 0, 0, 'L');
        $pdf->Cell(25, 4, "Turno", 0, 0, 'L');
        $pdf->Cell(70, 4, "Nome", 0, 0, 'L');
        $pdf->Cell(50, 4, "Relato", 0, 1, 'L');

        $lin = $pdf->GetY();
        $pdf->Line(10, $lin, 200, $lin);
        $pdf->ln(1);
        $pdf->SetFont('Arial', '', 9);
        while($tbl = pg_fetch_row($rs)){
            $NumRelat = $tbl[1];
            $DataIns =  $tbl[2];
            $CodTurno = $tbl[3];
            $NomeUsu = $tbl[5];
            $CodAnt = $tbl[6];
            $Relato = $tbl[7];
            $Ocor = $tbl[8];


            $rs0 = pg_query($Conec, "SELECT nomecompl FROM ".$xProj.".poslog WHERE pessoas_id = $CodAnt ");
            $tbl0 = pg_fetch_row($rs0);
            $row0 = pg_num_rows($rs0);
            if($row0 > 0){
                $NomeAnt = $tbl0[0];
            }else{
                $NomeAnt = "";
            }
            $rs1 = pg_query($Conec, "SELECT descturno FROM ".$xProj.".livroturnos WHERE codturno = $CodTurno ");
            $tbl1 = pg_fetch_row($rs1);
            $row1 = pg_num_rows($rs1);
            if($row1 > 0){
                $DescTurno = $tbl1[0];
            }else{
                $DescTurno = "";
            }

            $pdf->SetX(15);
            $pdf->Cell(20, 5, $NumRelat, 0, 0, 'L');
            $pdf->Cell(20, 5, $DataIns, 0, 0, 'L');
            $pdf->Cell(25, 5, $DescTurno, 0, 0, 'L');
            $pdf->Cell(70, 5, substr($NomeUsu, 0, 70), 0, 0, 'L');
            if($Ocor == 0){
                $pdf->Cell(30, 5, "Não houve.", 0, 1, 'L');
            }else{
                $pdf->Cell(30, 5, substr($Relato, 0, 30), 0, 1, 'L');
                $pdf->SetX(85);
                $pdf->MultiCell(0, 4, substr($Relato, 31), 0, 'J', false); //relato
            }
            $lin = $pdf->GetY();
            $pdf->Line(10, $lin, 200, $lin);
        }
    }else{
        $pdf->Cell(0, 4, "Nenhum registro encontado.", 0, 0, 'C');
    }
    $pdf->Output();
 }
