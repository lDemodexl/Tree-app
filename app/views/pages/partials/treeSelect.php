<?php if($data['roots']){?>
<select class="form-select form-select-lg mb-3" id="selectTree" onchange="changeTree(this)" aria-label="Change active tree">
    <?php foreach($data['roots'] as $key=>$root){
        ?>
        <option <?php echo $root->id==$_SESSION['active_tree']?'selected':'';?> value="<?php echo $root->id;?>"><?php echo $root->name;?></option>
        <?php
    }?>					
</select>
<?php } ?>