<?php

namespace Udacity;

trait ArraysTrait {

    /**
     * checks if an input array is a list with ascending ordered keys starting with 0
     * 
     * @param array $inputArr
     * @return boolean
     */
    public function arrayIsZeroIndexedOrderedList(array $inputArr): bool {
        $count = 0;
        foreach (array_keys($inputArr) as $key) {
            if ($key !== $count) {
                return false;
            }
            $count++;
        }
        return true;
    }

}