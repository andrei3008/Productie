<?php

/*--------------------------------------------------
	Generare Raport Trasferuri
--------------------------------------------------*/
	

		include "../classes/mpdf/mpdf.php";
		include '../classes/PHPExcel.php';
		include '../classes/PageClass.php';
        require_once "../autoloader.php";
        require_once('../classes/SessionClass.php');
        require_once('../includes/dbFull.php');
        $luna = $_POST['luna'];
        $luna_simplu = $_POST['luna'];
        $an = $_POST['an'];
        $ext = $_POST['ext'];
        $idOperator = $_POST['idOperator']; 
        // $luna = $_GET['luna'];
        // $luna_simplu = $_GET['luna'];
        // $an = $_GET['an'];
        // $ext = $_GET['ext'];
        // $idOperator = $_GET['idOperator'];

        $session = new SessionClass();
        $session->exchangeArray($_SESSION);
        $db = new dbFull(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
        $database = new DataConnection();
        $appSettings = $session = new SessionClass();
        $page = new PageClass();
        $page->checkLogin($_SESSION['username'], $_SESSION['operator']);
        $post = $db->sanitizePost($_POST);
        $data_start = date('Y-m-d', strtotime(date($an.'-'.$luna.'-01')));
        $data_end = date('Y-m-t', strtotime(date($an.'-'.$luna.'-01')));
        $luna = date("m",strtotime($data_start));
        $locatieInainte = new LocatiiEntity($database,$session);
        $locatieDupa = new LocatiiEntity($database,$session);
        if ($idOperator != '') {
            $transferuri = $db->returneazaTransferuriOperatori($data_start, $data_end, $idOperator);
        } else {
            $transferuri = $db->returneazaTransferuri($data_start, $data_end);
        }
        $totalTr = count($transferuri);
        if ($idOperator == 1)  { $nume_op = '- Ampera'; }
        elseif ($idOperator == 2) { $nume_op = '- Red Long'; }
        if($ext == "pdf"){
            $pdf = new mPDF('c', 'A4-P');
            
            //echo $luna;
            $out = '
                <style>
                    table td {
                        border: 1px solid;
                    }
                </style>
                <html lang="en">
                <body>
                <div class="col-md-10"> Raport transfer -  '.$page->getLuna($luna_simplu).' '.$an.' '.$nume_op.'
                <br />
                '.$totalTr.' trasferuri
                </div>
                <br></br>
                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Nr. Crt</th>
                                <th>Seria</th>
                                <th>Id Aparat Inainte</th>
                                <th>Id Aparat Dupa</th>
                                <th>Mutat de la</th>
                                <th>Mutat la</th>
                                <th>Adresa Inainte</th>
                                <th>Adresa Dupa</th>
                                <th>Data PVR</th>
                                <th>Data baza de date</th>
                            </tr>
                        </thead>';


                   
                    $i = 0;
                    foreach ($transferuri as $transfer) {
                        $i++;
                        $elementTransfer = new TransferAparate($transfer);
                        $out.= '<tr>
                                    <td>'. $i.'</td>
                                    <td>';
                      
                                        $aparat = new AparateMapper($database,$session);
                                        $aparatTransfer = $aparat->getAparat($elementTransfer->getIdApDupa());
                           $out .= $aparatTransfer->getSeria().'
                                    </td>
                                    <td class="align--center">'.  $elementTransfer->getIdApInainte().'</td>
                                    <td class="align--center">'.  $elementTransfer->getIdApDupa().'</td>
                                    <td>';
                            
                                        $locatieInainte->getLocatie($elementTransfer->getIdLocInainte());
                            $out .= $locatieInainte->getDenumire().'
                            
                                    </td>
                                    <td>';
                            $locatieDupa->getLocatie($elementTransfer->getIdLocDupa());
                              $out .=  $locatieDupa->getDenumire().'</td>
                                    <td style="font-size: 11px">' .$locatieInainte->getAdresa().'</td>
                                    <td style="font-size: 11px">' .$locatieDupa->getAdresa(). '</td>
                                    <td>' .$elementTransfer->getDtPVR(). '</td>
                                    <td>' .$elementTransfer->getDtBaza(). '</td>
                                </tr>';
                                
                            }
                           $out .= ' 
            
                        </tbody>
                    </table>

                </body>';
           // echo $out;
            $pdf->writeHTML($out);
	        $nume_fisier2 = 'Transfer-'.$data_start.'.pdf';
	        $pdf->output($nume_fisier2);
            echo $nume_fisier2;
        }else{
                    $excel = new PHPExcel();
                    $active_sheet = $excel->createSheet(1);
        			$excel->setActiveSheetIndex(1);
					/**
					 * 			MARGINI PRINT
					 */
        			$active_sheet->getPageMargins()->setTop(1);
					$active_sheet->getPageMargins()->setRight(0.75);
					$active_sheet->getPageMargins()->setLeft(0.75);
					$active_sheet->getPageMargins()->setBottom(1);
					$active_sheet->getPageSetup()->setFitToWidth(1);    
   					$active_sheet->getPageSetup()->setFitToHeight(0);
					/**
					 * 			LANDSCAPE & A4 PRINT
					 */
					$active_sheet
					    ->getPageSetup()
					    ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
					$active_sheet
					    ->getPageSetup()
					    ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
                     /**
					 * 			CAP TABEL
					 */
                    
            		$active_sheet->setCellValue('A1', "Raport transfer -  ".$page->getLuna($luna_simplu).' '.$an.' '.$nume_op);
                    $active_sheet->mergeCells('A1:J1');
                    $active_sheet->mergeCells('A2:J2');
					$active_sheet->setCellValue('A3', "Nr. Crt");
					$active_sheet->setCellValue('B3', "Seria");
					$active_sheet->setCellValue('C3', "Id Aparat Inainte");
					$active_sheet->setCellValue('D3', "Id Aparat Dupa");
					$active_sheet->setCellValue('E3', "Mutata de la");
					$active_sheet->setCellValue('F3', "Mutat la");
                    $active_sheet->setCellValue('G3', "Adresa Inainte");
					$active_sheet->setCellValue('H3', "Adresa Dupa");
					$active_sheet->setCellValue('I3', "Data PVR");
					$active_sheet->setCellValue('J3', "Data baza de date");
            
                     $i = 4;
                    foreach ($transferuri as $transfer) {
                        
                        $elementTransfer = new TransferAparate($transfer);
                        $active_sheet->setCellValue('A'.$i, ($i-3));
                        $aparat = new AparateMapper($database,$session);
                        $aparatTransfer = $aparat->getAparat($elementTransfer->getIdApDupa());
                        $active_sheet->setCellValue('B'.$i, $aparatTransfer->getSeria());
                        $active_sheet->setCellValue('C'.$i, $elementTransfer->getIdApInainte());
                        $active_sheet->setCellValue('D'.$i, $elementTransfer->getIdApDupa());
                        $locatieInainte->getLocatie($elementTransfer->getIdLocInainte());
                        $active_sheet->setCellValue('E'.$i, $locatieInainte->getDenumire());
                        $active_sheet->getColumnDimension('E')->setWidth(11);
                        $locatieDupa->getLocatie($elementTransfer->getIdLocDupa());
                        $active_sheet->setCellValue('F'.$i, $locatieDupa->getDenumire()); 
                        $active_sheet->getColumnDimension('F')->setWidth(11);
                        $active_sheet->setCellValue('G'.$i, $locatieInainte->getAdresa()); 
                        $active_sheet->getColumnDimension('G')->setWidth(32);
                        $active_sheet->setCellValue('H'.$i, $locatieDupa->getAdresa());
                        $active_sheet->getColumnDimension('H')->setWidth(32);
                        $active_sheet->setCellValue('I'.$i, $elementTransfer->getDtPVR());
                        $active_sheet->getColumnDimension('I')->setWidth(11);
                        $active_sheet->setCellValue('J'.$i, $elementTransfer->getDtBaza());
                        $active_sheet->getColumnDimension('J')->setWidth(11);
                        $i++;
                    }
                    /**
					 * 			STILURI FINALE
					 */
					$active_sheet->getStyle('A1:J'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$active_sheet->getStyle('A1:J'.($i))->getAlignment()->setWrapText(true);

					$active_sheet->getStyle("A1:J".($i))->applyFromArray(
					    array(
					        'borders' => array(
					            'allborders' => array(
					                'style' => PHPExcel_Style_Border::BORDER_THIN,
					                'color' => array('rgb' => '000000')
					            )
					        )
					    )
					);
                $nume_fisier2 = 'Transfer-'.$data_start.'.xlsx';
				$writer = new PHPExcel_Writer_Excel2007($excel);
				$writer->save($nume_fisier2);
                echo $nume_fisier2;
        }
		

?>
