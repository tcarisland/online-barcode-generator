<?php

    abstract class Barcode {

        /**
         * @var the value we want to encode
         */
        protected $value;
        /**
         * @var the binary sequence of the encoded value
         */
        protected $bin_seq;
        /**
         * @var the calculated check digit value
         */
        protected $check_digit;

        /**
         * @param $input the encoded barcode as a string of 1s and 0s
         * @return string the encoded barcode as a series of numbers representing bar widths
         */
        public function bin_to_dec($val) {
            $retval = "";
            $sum = 1;
            for($i = 1; $i < strlen($val); $i++) {
                $cur = $this->get_int_val($val, $i);
                $prev = $this->get_int_val($val, ($i - 1));
                if($cur != $prev) {
                    $retval .= $sum;
                    $sum = 1;
                } else {
                    $sum += 1;
                }
                if($i == (strlen($val) - 1)) {
                    $retval .= $sum;
                }
            }
            return $retval . "<br>";
        }

        /**
         * @param $val the string we want to get the value from
         * @param $pos the position of the character in the sequence
         * @return int the integer value of the character at position $pos in the string $val
         */
        public function get_int_val($val, $pos)
        {
            return intval(substr($val, $pos, 1), 10);
        }

        /**
         * @param $sequence the sequence of intervals of black and white
         * @return string the svg drawing in string form
         */
        public function generate_barcode_svg($sequence) {
            $width = 0;
            $height = 150;
            $offset = 0;
            $barwidth = 3;
            $pad = 20;
            for($i = 0; $i < strlen($sequence); $i++) {
                $width += $this->get_int_val($sequence, $i, 1) * $barwidth;
            }
            $svg_string = "<svg width='" . $width . "' height='" . ($height + 30) . "'>\n";
            for($i = 0; $i < strlen($sequence); $i++) {
                $curval = $this->get_int_val($sequence, $i, 1);
                $rectwidth = $curval * $barwidth;
                if($i % 2 == 0) {
                    $myheight = $height;
                    if($offset > 45 * $barwidth && $offset < 50 * $barwidth) {
                        $myheight += $pad;
                    }
                    if($offset < 3 * $barwidth || $offset > 90 * $barwidth) {
                        $myheight += $pad;
                    }
                    $rectangle = "<rect y='0' x='" . $offset . "' width='" . $rectwidth . "' height='" . $myheight . "' style='fill: rgb(0, 0, 0)'/>\n";
                } else {
                    $rectangle = "<rect y='0' x='" . $offset . "' width='" . $rectwidth . "' height='" . $height . "' style='fill: rgb(255, 255, 255)'/>\n";
                }
                $offset += $rectwidth;
                $svg_string .= $rectangle;
            }
            $svg_string .= "</svg>\n";
            return $svg_string;
        }

        /**
         * @param $input the value stored in the barcode
         * @return mixed
         */
        abstract public function calculate_checksum($input);

        /**
         * @param $input the value stored in the barcode
         * @return mixed a string containing 1s and 0s representing a white or black area with a width of 1 unit
         */
        abstract public function calculate_binary_sequence($input, $table);

    }

?>