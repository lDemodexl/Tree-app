<?php 

function buildTreeHtml($data){
    $html = '';
    foreach( $data as $tree ){
        $class = '';
        if( empty($tree['parentID']) ){
            $class .= 'root ';
        }else{
            $class .= 'child ';
        }

        if( !empty($tree['childs']) ){
            $class .= 'parent ';
        }
        $html .= '<ul class="p-l-05" >';
            $html .= '<li id="'.$tree['id'].'" class="'.$class.'">';
            if( !empty($tree['childs']) ){
                
                $html .= '<input type="checkbox" class="hidden" onchange="saveDropdown(this)"  id="marker_'.$tree['id'].'" name="marker_'.$tree['id'].'" value="1">';
                $html .= '<label for="marker_'.$tree['id'].'" class="fas marker"></label>';
            }

            $html .= '<span class="p-r-05">
                <a href="javascript:void(0)" class="show-element" id="info_'.$tree['id'].'">
                <span onclick="toogleNameInput('.$tree['id'].')">'.$tree['name'].'</span>';

            $html .= '<span class="addChild" onclick="addChild(this);"><i class="fas fa-plus icon-xs"></i></span>'; 

            if( !empty($tree['parentID']) ){
                $html .= '<span class="removeElement" onclick="deleteElement(this);"><i class="fas fa-trash icon-xs"></i></span>';
            }
            $html .= '</a>';
            $html .= '<span style="display:none" class="edit-element" id="edit_'.$tree['id'].'">
                <input type="text" name="'.$tree['id'].'" value="'.$tree['name'].'">
                <span class="editElement" onclick="editElement(this);"><i class="fas fa-check"></i></span>
                <span class="editElement" onclick="hideAllNameInputs();"><i class="fas fa-times"></i></span>
                </span>';

            $html .= '</span>';

            if( !empty($tree['childs']) ){
                $html .= buildTreeHtml($tree['childs']);
            }
            $html .='</li>';
        $html .='</ul>';
    } 
    return $html;
}

if( !empty($data['tree']) ){
    echo buildTreeHtml($data['tree']);
}else{
    ?><button class="btn btn-primary mb-3" onclick="createRoot()">Create Root</button><?php
}
?>