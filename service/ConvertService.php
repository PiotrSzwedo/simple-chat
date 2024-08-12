<?php

class ConvertService{
    public function convertArrayByKey($array, $key){
        $elements = [];

        foreach ($array as $field){
            $elements[$field[$key]][] = $field;
        }

        return $elements;
    }

    public function convertToStringByKey($array, $keyToSort, $keyToGet){
        $elements = [];

        foreach ($array as $field){
            $elements[0][$field[$keyToSort]] = $field[$keyToGet];
        }

        return $elements;
    }

    public function prateArray($array){
        $elements = [];

        foreach ($array as $key=>$data){
            $elements[] = $this->prase($data);
        }

        return $elements;
    }

    private function prase($data){
        $elements = [];

        if (is_array($data)){
            foreach ($data as $field){
                if (is_array($field)){
                    $elements[] = $this->prase($field);
                }else{
                    $elements[] = $field;
                }
            }
        }else{
            $elements[] = $data;
        }


        return $elements;
    }
}