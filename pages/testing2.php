<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include "fpdf.php";
include " ../../ library/config. php";
require_once (".. /model/ dbconn. php");
require_once (".. /model/ pos. php");

if(!isset($_POST['id_sales'])) 
{
	die("Sorry, Browser can not be refreshed for data security. Please reprint through reprint form!!");
}
$id_sales=$_POST['id_sales'];

$pos = new pos();
$sale = $pos->getSaleId($id_sales);
if($sale[0] == false)
{
	die('Error : '.$sale[1]);
}
$transaction_date = $sale[1]['sale_date'];
$customer = 'Customer';
$disc_rp =$sale[1]['disc_rp'];
$tax =0;
$note=$sale[1]['note'];
$nopage = 1;
$nopagelast = 0; 
$cashier = $sale[1]['username'];
if($_POST['duplicate']==1){
	$duplicate = '  (Duplicate Note)';
}else{
	$duplicate = '';
}

$xshop = $pos->getrefsytem();
$shop = $xshop[1]['name_shop'];
$address = $xshop[1]['address_shop'];
$phone = $xshop[1]['phone_shop'];

class PDF extends FPDF
{
	function AddPage($orientation='', $format='')
	{
		if($this->state==0)
			$this->Open();
		$family=$this->FontFamily;
		$style=$this->FontStyle.($this->underline ? 'U' : '');
		$size=$this->FontSizePt;
		$lw=$this->LineWidth;
		$dc=$this->DrawColor;
		$fc=$this->FillColor;
		$tc=$this->TextColor;
		$cf=$this->ColorFlag;
		if($this->page>0)
		{
			$this->InFooter=true;
			$this->Footer();
			$this->InFooter=false;
			$this->_endpage();
		}
		$this->_beginpage($orientation,$format);
		$this->_out('2 J');
		$this->LineWidth=$lw;
		$this->_out(sprintf('%.2F w',$lw*$this->k));
		if($family)
			$this->SetFont($family,$style,$size);
		$this->DrawColor=$dc;
		if($dc!='0 G')
			$this->_out($dc);
		$this->FillColor=$fc;
		if($fc!='0 g')
			$this->_out($fc);
		$this->TextColor=$tc;
		$this->ColorFlag=$cf;
		$this->InHeader=true;
		$this->Header();
		$this->InHeader=false;
		if($this->LineWidth!=$lw)
		{
			$this->LineWidth=$lw;
			$this->_out(sprintf('%.2F w',$lw*$this->k));
		}
		if($family)
			$this->SetFont($family,$style,$size);
		if($this->DrawColor!=$dc)
		{
			$this->DrawColor=$dc;
			$this->_out($dc);
		}
		if($this->FillColor!=$fc)
		{
			$this->FillColor=$fc;
			$this->_out($fc);
		}
		$this->TextColor=$tc;
		$this->ColorFlag=$cf;
	}
	function Header()
	{
		global $transaction_date,$customer,$id_sales,$shop,$address,$phone;
		$this->SetFont('Courier','',9);
		$this->setXY(10,10);
		$this->Cell(20,0,'No Trans.', 0, '0', 'L');
		$this->Cell(10,0,':', 0, '0', 'C');
		$this->Cell(20,0,$id_sales, 0, '0', 'L');
		$this->setXY(10,15);
		$this->Cell(20,0,'Date', 0, '0', 'L');
		$this->Cell(10,0,':', 0, '0', 'C');
		$this->Cell(20,0,date('d/m/Y',strtotime($transaction_date)), 0, '0', 'L');
		$this->setXY(10,20);
		$this->Cell(20,0,'Customer', 0, '0', 'L');
		$this->Cell(10,0,':', 0, '0', 'C');
		$this->Cell(20,0,$customer, 0, '0', 'L');

		$this->SetFont('Arial','',14);
		$this->setXY(130,8);
		$this->Cell(20,0,$shop, 0, '0', 'L');
		$this->SetFont('Courier','',9);
		$this->setXY(130,12);
		$this->MultiCell(80,3,$address, 0, 'L', false);
		$this->setXY(130,21);
		$this->Cell(20,0,'Phone : '.$phone, 0, '0', 'L');
		$this->SetFont('Courier','',9);
		$this->SetDash(1,1);
		$this->line(10,25,206,25); 
		$this->Ln(20);
	}
	function Footer()
	{
		global $duplicate,$note,$nopagelastx,$cashier;

		$this->SetY(-7);
		$this->SetDash(1,1);
		$this->line(10,130,206,130); 
		$this->SetFont('Arial','I',7);
		$this->Cell(0,0,'cashier : '.$cashier.' , Hal : '.$this->PageNo().'/{nb}'.$duplicate,0,0,'l');
		$this->SetXY(10,-4);
		$this->write(0,date('d/m/Y H:i'));
		$this->SetY(-15);
		$last = count($this->pages);
		if($this->PageNo() == $nopagelastx){		
			$this->SetY(-20);
			$this->cell(40,0,'Cashier/Manager', 0, 'L', false);
			$this->cell(0,0,'Customer', 0, 'L', false);
			$this->SetY(-35);
			$this->Cell(40,0,'Best Regard ,',0,0,'l');
			$this->Cell(0,0,'Receiver ,',0,0,'l');
		}
	}
	function SetDash($black=null, $white=null)
	{
		if($black!==null)
			$s=sprintf('[%.3F %.3F] 0 d',$black*$this->k,$white*$this->k);
		else
			$s='[] 0 d';
		$this->_out($s);
	}
	function header_note()
	{
		global $y,$baris;
		$this->SetFont('Courier','',9);
		$this->setXY(10,27);
		$this->Cell(7,0,'NO', 0, '0', 'L');
		$this->Cell(100,0,'Item', 0, '0', 'C');
		$this->Cell(15,0,'Qty', 0, '0', 'C');
		$this->Cell(30,0,'Price', 0, '0', 'R');
		$this->Cell(15,0,'Disc%', 0, '0', 'C');
		$this->Cell(30,0,'Total', 0, '0', 'R');
		$this->SetDash(1,1);
		$this->line(10,30,206,30); 
	}
	function AddPageNew()
	{
		global $y,$baris,$default_y,$nopage;
		$this->AddPage('P','struck');
		$this->AliasNbPages();
		$y = $default_y;
		$baris=1;
		$nopage++;
	}

}

$pdf=new PDF();
$pdf->SetAutoPageBreak(true,1);
$pdf->AddPage('P','struck');
$pdf->AliasNbPages();
$pdf->header_note();
$pdf->isFinished = true;
$default_y = 34;
$y = $default_y;
$default_footer = 19;
$baris = 1;
$subtotal=0;
$detail_sale=$pos->getSaleDetailIdSale($id_sales);
foreach ($detail_sale[1] as $key) {
	if($baris >= 16 )
	{
		$pdf->line(10,$y,206,$y); 
		$pdf->setXY(157,$y+3);
		$pdf->Cell(20,0,'Subtotal : Rp.', 0, '0', 'R');
		$pdf->Cell(30,0,number_format($subtotal), 0, '0', 'R');
		$pdf->setXY(187,$y+8);
		$pdf->SetFont('Courier','',7);
		$nexthal =$nopage + 1;
		$pdf->Cell(20,0,'Next page : '.$nexthal, 0, '0', 'R');
		$pdf->SetFont('Courier','',9);
		$pdf->AddPage('P','struck');
		$pdf->AliasNbPages();
		$pdf->header_note();
		$y = $default_y;
		$baris=1;
		$nopage++;
	}
	$pdf->setXY(10,$y);
	$pdf->Cell(7,0,$key['urutan'].'.', 0, '0', 'L');
	$pdf->Cell(100,0,substr($key['item_name'], 0,60), 0, '0', 'L');
	$pdf->Cell(15,0,number_format($key['qty']).' '.$key['unit'], 0, '0', 'C');
	$pdf->Cell(30,0,number_format($key['price']), 0, '0', 'R');
	$pdf->Cell(15,0,number_format($key['disc_prc'],2), 0, '0', 'C');
	$pdf->Cell(30,0,number_format($key['total']), 0, '0', 'R');
	$y+=5;
	$baris++;
	$subtotal+=$key['total'];
}
$y-=2;
$pdf->SetDash(1,1);
$pdf->line(10,$y,206,$y); 
if($baris>=$default_footer) 
{
	$pdf->AddPageNew();
}else
{
	$y+=5;		
}

$pdf->setXY(157,$y);
$pdf->Cell(20,0,'Subtotal : Rp.', 0, '0', 'R');
$pdf->Cell(30,0,number_format($subtotal), 0, '0', 'R');


$baris++;

if($baris>=$default_footer) 
{
	$pdf->AddPageNew();
}else
{
	$y+=5;		
}

$pdf->setXY(157,$y);
$pdf->Cell(20,0,'Disc : Rp.', 0, '0', 'R');
$pdf->Cell(30,0,number_format($disc_rp), 0, '0', 'R');
$pdf->SetDash(1,1);
$pdf->line(175,$y+2,206,$y+2); 
$baris++;
if($baris>=$default_footer) 
{
	$pdf->AddPageNew();
}else
{
	$y+=5;		
}
$nopagelast=$nopage;
$pdf->setXY(157,$y);
$pdf->Cell(20,0,'Total : Rp.', 0, '0', 'R');
$pdf->SetFont('Courier','B',9);
$pdf->Cell(30,0,number_format($subtotal - $disc_rp), 0, '0', 'R');
$pdf->SetFont('Courier','',9);
$xtotal = $subtotal - $disc_rp;
if($tax >0)
{
	$nopagelast=$nopage;
	$pdf->setXY(157,$y);
	$pdf->Cell(20,0,'Total : Rp.', 0, '0', 'R');
	$pdf->SetFont('Courier','B',9);
	$pdf->Cell(30,0,number_format($subtotal - $disc_rp), 0, '0', 'R');
	$pdf->SetFont('Courier','',9);
	$xtotal = $subtotal - $disc_rp;
	$baris++;
	if($baris>=$default_footer) 
	{
		$pdf->AddPageNew();
	}else
	{
		$y+=5;		
	}
	$nopagelast=$nopage;
	$pdf->setXY(157,$y);
	$pdf->Cell(20,0,'tax : Rp.', 0, '0', 'R');
	$pdf->SetFont('Courier','B',9);
	$pdf->Cell(30,0,number_format($tax), 0, '0', 'R');
	$pdf->SetFont('Courier','',9);
	$baris++;

	$pdf->SetDash(1,1);
	$pdf->line(175,$y+2,206,$y+2); 
	if($baris>=$default_footer) 
	{
		$pdf->AddPageNew();
	}else
	{
		$y+=5;		
	}
	$nopagelastx=$nopage;
	$pdf->setXY(157,$y);
	$pdf->Cell(20,0,'Grand Total : Rp.', 0, '0', 'R');
	$pdf->SetFont('Courier','B',9);
	$pdf->Cell(30,0,number_format($xtotal + $tax), 0, '0', 'R');
	$pdf->SetFont('Courier','',9);
}else
{
	$nopagelastx=$nopage;
	$pdf->setXY(157,$y);
	$pdf->Cell(20,0,'Total : Rp.', 0, '0', 'R');
	$pdf->SetFont('Courier','B',9);
	$pdf->Cell(30,0,number_format($subtotal - $disc_rp), 0, '0', 'R');
	$pdf->SetFont('Courier','',9);
	$xtotal = $subtotal - $disc_rp;
}

$pdf->Output();