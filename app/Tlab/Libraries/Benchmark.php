<?php

namespace Tlab\Libraries;

class Benchmark
{
    public $marker = array();

    // --------------------------------------------------------------------

    /**
     * Set a benchmark marker.
     *
     * Multiple calls to this function can be made so that several
     * execution points can be timed
     *
     * @param string $name name of the marker
     */
    public function mark($name)
    {
        $this->marker[$name] = microtime();
    }

    // --------------------------------------------------------------------

    /**
     * Calculates the time difference between two marked points.
     *
     * If the first parameter is empty this function instead returns the
     * {elapsed_time} pseudo-variable. This permits the full system
     * execution time to be shown in a template. The output class will
     * swap the real value for this variable.
     *
     * @param	string	a particular marked point
     * @param	string	a particular marked point
     * @param	int	the number of decimal places
     *
     * @return mixed
     */
    public function elapsed_time($point1 = '', $point2 = '', $decimals = 4)
    {
        if ($point1 == '') {
            return '{elapsed_time}';
        }

        if (!isset($this->marker[$point1])) {
            return '';
        }

        if (!isset($this->marker[$point2])) {
            $this->marker[$point2] = microtime();
        }

        list($sm, $ss) = explode(' ', $this->marker[$point1]);
        list($em, $es) = explode(' ', $this->marker[$point2]);

        return number_format(($em + $es) - ($sm + $ss), $decimals);
    }

    // --------------------------------------------------------------------

    /**
     * Memory Usage.
     *
     * This function returns the {memory_usage} pseudo-variable.
     * This permits it to be put it anywhere in a template
     * without the memory being calculated until the end.
     * The output class will swap the real value for this variable.
     *
     * @return string
     */
    public function memory_usage()
    {
        return memory_get_usage();
    }
}
