<?php
	class data_rom {
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
		public function short_zi_ro($zi) {
			switch (date('N', strtotime(date($zi)))) {
				case '1': $short_zi = 'L'; break;
				case '2': $short_zi = 'Ma'; break;
				case '3': $short_zi = 'Mi'; break;
				case '4': $short_zi = 'J'; break;
				case '5': $short_zi = 'V'; break;
				case '6': $short_zi = 'S'; break;
				case '7': $short_zi = 'D'; break;
			}
			return $short_zi;
		}
	}
?>