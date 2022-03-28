<?php

namespace App\Components;

class Recusive
{
    public $htmlSelect;
    
    public function recusive($data, $parentData, $parent_id = 0, $char ='')
    {
        foreach ($data as $key => $item)
        {
            // Nếu là chuyên mục con thì hiển thị
            if ($item['parent_id'] == $parent_id)
            {
                if(!empty($parentData) && $parentData == $item['id']){
                    $this->htmlSelect .=  '<option selected value="'.$item['id'].'">'
                                .$char . $item['name'].'</option>';
     
                }else{
                    $this->htmlSelect .=  '<option value="'.$item['id'].'">'
                                .$char . $item['name'].'</option>';

                } 
                                                 
                // Xóa chuyên mục đã lặp
                unset($data[$key]);
                 
                // Tiếp tục đệ quy để tìm chuyên mục con của chuyên mục đang lặp
                $this->recusive($data, $parentData, $item['id'], $char.'|---');  
            }
        }
        return $this->htmlSelect;
    }
}