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
            $html .= '<span class="p-r-05"><a>'.$tree['name'].$tree['id'];
            $html .= '<span class="addChild" onclick="addChild(this);"><i class="fas fa-plus icon-xs"></i></span><span class="removeElement" onclick="deleteElement(this);"><i class="fas fa-trash icon-xs"></i></span></a></span>';
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