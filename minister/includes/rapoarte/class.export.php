<?php
	class export {
		public function __construct($db, $stuff, $date = array(), $rapoarte, $pdf, $excel) {
            $this->db 		= $db;
            $this->stuff 	= $stuff;
            $this->ext 		= $date[ext];
            $this->locatie 	= intval($date[locatie]);
            $this->firma 	= intval($date[firma]);
            $this->tip 		= $date[tip];
            $this->data 	= $date[data];
            $this->an 		= $date[an];
            $this->luna 	= $date[luna];
            $this->perioada = $date[perioada];
            $this->operator = $date[operator];
            $this->rapoarte= $rapoarte;
            $this->pdf 		= $pdf;
            $this->excel 	= $excel;
        }
        public function generare_export() {
        	// error_reporting(E_ALL);
        	
        	if ($this->perioada == 'zilnic') {
        		$data_select = $this->data;
        	} elseif ($this->perioada == 'lunar') {
        		$data_select=array();
				for($d=1; $d<=31; $d++)
				{
				    $time = mktime(12, 0, 0, $this->luna, $d, $this->an);          
				    if (date('m', $time) == $this->luna)       
				        $data_select[] = "'".date('Y-m-d', $time)."'";
				}
        	}
        	if ($this->tip == 'sil') {
				$data_select = array('0' => $data_select[0]);
			}
        		$situatia = $this->rapoarte->generare_raport(
									array(
										'data_select' => $data_select, 
										'firma_select' => $this->firma,
										'firma' => $this->firma, 
										'locatie' => $this->locatie,
										'tip'=> $this->tip,
										'operator'=>  $this->operator,
										'de_unde'=>  'export'
									)
							);
	        	$situatia = json_decode($situatia);
	        	$situatia = $situatia->out;
	        	$date_operator = $this->rapoarte->get_date_operator(array('id' => $this->operator));
	        	$date_operator = json_decode($date_operator);
	        	$date_locatie = $this->rapoarte->get_date_locatie(array('id' => $this->locatie));
	            $date_locatie = json_decode($date_locatie);

	            $implode_data = $this->stuff->clean_link(implode('-', $this->data));
	            if ($this->tip == 'siz') {
	            	$nume_fisier = $implode_data.'-'.$this->stuff->clean_link($date_operator->denumire).'-'.$this->stuff->clean_link($date_locatie->adresa);
	            } else {
	            	$nume_fisier = $this->an.'-'.$this->luna.'-'.$this->stuff->clean_link($date_operator->denumire).'-'.$this->stuff->clean_link($date_locatie->adresa);
	            }
        	if ($this->ext == 'pdf') {
        		// EXPORT TO PDF
	        		$this->pdf->writeHTML('<link rel="stylesheet" type="text/css" href="rapoarte.css">'.$situatia);
	        		$nume_fisier2 = $nume_fisier.'.pdf';
	        		$this->pdf->output('../../'.$nume_fisier2);
        		// ---------------------------------------------------
        	} elseif ($this->ext == 'xls') {
				
				$i = 0;
				foreach ($data_select as $key => $data_sel) {
					$total_incasari = $jackpot = $total = '';
                	$dataa = str_replace("'", "", $data_sel);
                	// $active_sheet = $this->excel->getActiveSheet();
                	$active_sheet = $this->excel->createSheet($i);
        			$this->excel->setActiveSheetIndex($i);
        			/**
        			 * 			PROTECT WORKSHEET
        			 */
        				$active_sheet->getProtection()->setSheet(true);
						$active_sheet->getProtection()->setSort(true);
						$active_sheet->getProtection()->setInsertRows(true);
						$active_sheet->getProtection()->setFormatCells(true);

						$active_sheet->getProtection()->setPassword('red2016');
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
					 * 			DATE LOCATIE SI OPERATOR
					 */
					$date_locatie = $this->rapoarte->get_date_locatie(array('id' => $this->locatie));
            		$date_locatie = json_decode($date_locatie);

            		$date_operator = $this->rapoarte->get_date_operator(array('id' => $this->operator));
            		$date_operator = json_decode($date_operator);
            		/**
					 * 			CAP TABEL - DATE OPERATOR SI LOCATIE
					 */
            		$active_sheet->setCellValue('A1', "Organizator: ".$date_operator->denumire);
					$active_sheet->setCellValue('A2', "Domiciliul fiscal: ".$date_operator->adresa);
					$active_sheet->setCellValue('A3', "Cod de identitate fiscala: ".$date_operator->cui);
					$active_sheet->setCellValue('A4', "Nr. Reg Com: ".$date_operator->reg);
					$active_sheet->setCellValue('A5', "Capital Social: ".$date_operator->capital);
					$active_sheet->setCellValue('A6', "Licenta: ".$date_operator->licenta);
					$active_sheet->setCellValue('A7', "Adresa punct lucru: ".$date_locatie->adresa);
					for ($j=1; $j <= 7; $j++) { 
	        			$this->excel->setActiveSheetIndex($i)->mergeCells('A'.$j.':Q'.$j);
	        		}
	        		$dat = ($this->tip == 'siz') ?  date('d-m-Y', strtotime($dataa)) : $this->luna_ro($this->luna).'-'.$this->an;
					$active_sheet->setCellValue('R1',"Data: ".$dat);
					$this->excel->setActiveSheetIndex($i)->mergeCells('R1:S7');

					/**
					 * 			CAP TABEL SITUATIE
					 */
					if ($this->tip == 'siz') {
						$tit = 'SITUATIA INCASARILOR (VENITURILOR) ZILNICE';
						$tit2 = 'INCASARI (VENITURI) ZILNICE SLOT MACHINE';
						$tit3 = 'TOTAL CASTIGURI JACKPOT ACORDATE ZILNIC';
						$tit4 = 'TOTAL INCASARI (VENITURI) ZILNICE';
					} else {
						$tit = 'SITUATIA INCASARILOR (VENITURILOR) LUNARE';
						$tit2 = 'INCASARI (VENITURI) LUNARE SLOT MACHINE';
						$tit3 = 'TOTAL CASTIGURI JACKPOT ACORDATE LUNAR';
						$tit4 = 'TOTAL INCASARI (VENITURI) LUNARE';
					}
					$active_sheet->setCellValue('A8', $tit);
					$active_sheet->setCellValue('A9', 'obtinute din activitatea de exploatare a jocurilor de noroc slot machine (lei)');
					$active_sheet->getStyle("A8:A9")->getFont()->setBold(true);
					$active_sheet->getStyle('A8:A9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$active_sheet->mergeCells('A8:S8');
					$active_sheet->mergeCells('A9:S9');

					$active_sheet->setCellValue('A10','Nr. Crt.');
					$active_sheet->getColumnDimension('Q')->setWidth(3.3);
					$active_sheet->mergeCells('A10:A11');
					$active_sheet->setCellValue('B10','Seria mijlocului de joc');
					$active_sheet->mergeCells('B10:B11');
					$active_sheet->setCellValue('C10',"Indexul contoarelor de inceput (Si)");
					$active_sheet->mergeCells('C10:E10');
					$active_sheet->setCellValue('C11','I');
					$active_sheet->setCellValue('D11','Ej');
					$active_sheet->setCellValue('E11','Ei');
					$active_sheet->setCellValue('F10', "Indexul contoarelor de inceput (Sf)");
					$active_sheet->mergeCells('F10:H10');
					$active_sheet->setCellValue('F11','I');
					$active_sheet->setCellValue('G11','Ej');
					$active_sheet->setCellValue('H11','Ei');
					$active_sheet->setCellValue('I10',"Factor de multiplicare (F)");
					$active_sheet->mergeCells('I10:K10');
					$active_sheet->setCellValue('I11','I');
					$active_sheet->setCellValue('J11','Ej');
					$active_sheet->setCellValue('K11','Ei');
					$active_sheet->getColumnDimension('I')->setWidth(5);
					$active_sheet->getColumnDimension('J')->setWidth(5);
					$active_sheet->getColumnDimension('K')->setWidth(5);
					$active_sheet->setCellValue('L10',"Diferenta indexuri \n (D) = (Sf-Si)xF");
					$active_sheet->mergeCells('L10:N10');
					$active_sheet->setCellValue('L11','I');
					$active_sheet->setCellValue('M11','Ej');
					$active_sheet->setCellValue('N11','Ei');
					$active_sheet->setCellValue('O10',"Sold impulsuri");
					$active_sheet->setCellValue('P10',"Pret / impuls");
					$active_sheet->getColumnDimension('P')->setWidth(6);
					$active_sheet->setCellValue('Q10',"Taxa de participare colectata (T)");
					$active_sheet->getColumnDimension('Q')->setWidth(10);
					$active_sheet->setCellValue('R10',"Total plati efectuate catre jucatori (P)");
					$active_sheet->setCellValue('S10',"Incasari (venituri) (lei) ");
					
					$active_sheet->getStyle("A10:S12")->getFont()->setSize(10);
					$active_sheet->getStyle('A10:S12')->applyFromArray(
					    array(
					        'fill' => array(
					            'type' => PHPExcel_Style_Fill::FILL_SOLID,
					            'color' => array('rgb' => 'F5F5F5')
					        )
					    )
					);-+
					/**
					 * 			INDEX COLOANE
					 */
					$inc = 0;
					for($col = 'A'; $col !== 'T'; $col++) {
					    $active_sheet->setCellValue($col.'12', $inc);
					    $inc++;
					}
					/**
					 * 			RAPORT INCASARI
					 */
					$date_incasari = ($this->tip == 'siz') ? $this->rapoarte->date_incasari($dataa, $this->locatie, 'export') : $this->rapoarte->date_incasari_sil($dataa, $this->locatie, 'export');
					// $date_incasari =  $this->rapoarte->date_incasari($dataa, $this->locatie, 'export');
					$row_curr = 13;
					foreach ($date_incasari as $k => $val) {
						$active_sheet->setCellValue('A'.$row_curr, $k);
						$active_sheet->setCellValue('B'.$row_curr, $val[serie_aparat]);
						$active_sheet->setCellValue('C'.$row_curr, $val[sii]);
						$active_sheet->setCellValue('D'.$row_curr, $val[siej]);
						$active_sheet->setCellValue('E'.$row_curr, $val[siei]);
						$active_sheet->setCellValue('F'.$row_curr, $val[sfi]);
						$active_sheet->setCellValue('G'.$row_curr, $val[sfej]);
						$active_sheet->setCellValue('H'.$row_curr, $val[sfei]);
						$active_sheet->setCellValue('I'.$row_curr, $val[fm_mec_i]);
						$active_sheet->setCellValue('J'.$row_curr, $val[fm_mec_ej]);
						$active_sheet->setCellValue('K'.$row_curr, $val[fm_mec_ei]);
						$active_sheet->setCellValue('L'.$row_curr, $val[di]);
						$active_sheet->setCellValue('M'.$row_curr, $val[dej]);
						$active_sheet->setCellValue('N'.$row_curr, $val[dei]);
						$active_sheet->setCellValue('O'.$row_curr, $val[si]);
						$active_sheet->setCellValue('P'.$row_curr, $val[pi_mec]);
						$active_sheet->setCellValue('Q'.$row_curr, $val[t]);
						$active_sheet->setCellValue('R'.$row_curr, $val[p]);
						$active_sheet->setCellValue('S'.$row_curr, $val[venit]);

						$total_incasari += $val[venit];
                		$jackpot += $val[jackpot];
						$row_curr++;
					}
					$total = $total_incasari + $jackpot;
					for($col = 'A'; $col !== 'S'; $col++) {
					    $active_sheet->getColumnDimension($col)->setAutoSize(false);
					}
					/**
					 * 			DIMENSIUNI COLOANE FIXE
					 */
					$active_sheet->getColumnDimension('A')->setWidth(5);
					$active_sheet->getColumnDimension('B')->setWidth(9);
					$active_sheet->getColumnDimension('D')->setWidth(2.5);
					$active_sheet->getColumnDimension('G')->setWidth(2.5);
					$active_sheet->getColumnDimension('J')->setWidth(2.5);
					$active_sheet->getColumnDimension('M')->setWidth(2.8);
					$active_sheet->getRowDimension('10')->setRowHeight(50);
					$active_sheet->getRowDimension('11')->setRowHeight(15);
					 
					/**
					 * 			FOOTER TABEL - TOTALURI
					 */
					$active_sheet->setCellValue('A'.$row_curr, $tit2);
					$active_sheet->setCellValue('A'.($row_curr+1), $tit3);
					$active_sheet->setCellValue('A'.($row_curr+2), $tit4);
					$active_sheet->setCellValue('S'.$row_curr, $total_incasari);
					$active_sheet->setCellValue('S'.($row_curr+1), $jackpot);
					$active_sheet->setCellValue('S'.($row_curr+2), $total);
					$active_sheet->mergeCells('A'.$row_curr.':R'.$row_curr);
					$active_sheet->mergeCells('A'.($row_curr+1).':R'.($row_curr+1));
					$active_sheet->mergeCells('A'.($row_curr+2).':R'.($row_curr+2));

					/**
					 * 			STILURI FINALE
					 */
					$active_sheet->getStyle('A10:S'.($row_curr+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$active_sheet->getStyle('A1:S'.($row_curr+2))->getAlignment()->setWrapText(true);

					$active_sheet->getStyle("A10:S".($row_curr+2))->applyFromArray(
					    array(
					        'borders' => array(
					            'allborders' => array(
					                'style' => PHPExcel_Style_Border::BORDER_THIN,
					                'color' => array('rgb' => '000000')
					            )
					        )
					    )
					);
					/**
					 * ------------------------------------------------------------------------------------
					 */
					$active_sheet->setTitle($dataa);

					$i++;
        		}
        		
				$nume_fisier2 = $nume_fisier.'.xlsx';
				$writer = new PHPExcel_Writer_Excel2007($this->excel);
				$writer->save('../../'.$nume_fisier2);
        	}

        	return $nume_fisier2;
        }
		
		public function luna_ro($luna) {
			switch ($luna) {
				case '01': $luna = 'Ianuarie'; break;
				case '02': $luna = 'Februarie'; break;
				case '03': $luna = 'Martie'; break;
				case '04': $luna = 'Aprilie'; break;
				case '05': $luna = 'Mai'; break;
				case '06': $luna = 'Iunie'; break;
				case '07': $luna = 'Iulie'; break;
				case '08': $luna = 'August'; break;
				case '09': $luna = 'Septembrie'; break;
				case '10': $luna = 'Octombrie'; break;
				case '11': $luna = 'Noiembrie'; break;
				case '12': $luna = 'Decembrie'; break;
			}
			return $luna;
		}
		public function get_title_situatie() {
			$out = "SITUATIA INCASARILOR (VENITURILOR) ZILNICE \n obtinute din activitatea de exploatare a jocurilor de noroc slot machine (lei)";
	        return $out;
		}
	}
?>