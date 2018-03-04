<?php

    require_once "barcode_parent_class.php";

    class EAN13 extends Barcode
    {

        function __construct($val) {
            $this->value = $val;
        }

        public function generate_ean()
        {
            return $this->control_ean($this->value) . "<br>\n";
        }

        public function control_ean($val)
        {
            $val = preg_replace("/[^0-9]/", "", $val);
            $control_ean_len = strlen($val);
            if ($control_ean_len >= 12) {
                return $this->encode_ean(substr($val, 0, 12));
            } else {
                return $val . " does not contain enough numeric characters";
            }
        }

        public function encode_ean($val)
        {
            $my_table = $this->create_ean_table();
            $check_digit = $this->calculate_checksum($val);

            $val = $val . $check_digit;

            $this->bin_seq = $this->calculate_binary_sequence($val, $my_table);

            $my_retval .= substr($val, 0, 1) . " " . substr($val, 1, 6) . " " . substr($val, 7, 6) . "<br>\n";

            $my_retval .= parent::generate_barcode_svg(parent::bin_to_dec($this->bin_seq)) . "<br>\n";
            return $my_retval;
        }

        public function calculate_binary_sequence($val, $table)
        {
            $lgr_sequence = $table["LGR"][$this->get_int_val($val, 0)];
            $this->bin_seq = "101";
            for ($i = 1; $i < 7; $i++) {
                $q = $i - 1;
                $this->bin_seq .= $table[$lgr_sequence[$q]][$this->get_int_val($val, $i)];
            }
            $this->bin_seq .= "01010";
            for ($i = 7; $i < 13; $i++) {
                $this->bin_seq .= $table["R"][$this->get_int_val($val, $i)];
            }
            $this->bin_seq .= "101";
            return $this->bin_seq;
        }

        /**
         * Calculates the EAN check digit
         * @param $val the value we want stored in an EAN13 barcode
         * @return int the check digit
         */
        public function calculate_checksum($val)
        {
            $sum = 0;
            for ($i = 0; $i < 12; $i++) {
                $my_val = parent::get_int_val($val, $i);
                if ($i % 2 == 0) {
                    $sum += $my_val * 1;
                } else {
                    $sum += $my_val * 3;
                }
            }
            $this->check_digit = (10 - ($sum % 10));
            return $this->check_digit;
        }

        /**
         * Creates a binary encoding table.
         * @return array an associative array containing both the LGR sequences and the binary sequences for the respective L G and R values from 0 to 9
         */
        public function create_ean_table()
        {
            $ean_table = array();
            $ean_table["L"] = array("0001101", "0011001", "0010011", "0111101", "0100011", "0110001",
                "0101111", "0111011", "0110111", "0001011");
            $ean_table["R"] = array("1110010", "1100110", "1101100", "1000010", "1011100", "1001110",
                "1010000", "1000100", "1001000", "1110100");
            $ean_table["G"] = array("0100111", "0110011", "0011011", "0100001", "0011101", "0111001",
                "0000101", "0010001", "0001001", "0010111");
            $ean_table["LGR"] = array("LLLLLLRRRRRR", "LLGLGGRRRRRR", "LLGGLGRRRRRR", "LLGGGLRRRRRR", "LGLLGGRRRRRR",
                "LGGLLGRRRRRR", "LGGGLLRRRRRR", "LGLGLGRRRRRR", "LGLGGLRRRRRR", "LGGLGLRRRRRR");
            return $ean_table;
        }

    }

?>