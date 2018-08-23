<?php if (!defined('THINK_PATH')) exit(); if(empty($houses)): ?><div class="message">
        <span>暂无精选房源！</span>
    </div>
<?php else: ?>
    <?php if(is_array($houses)): $i = 0; $__LIST__ = $houses;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$house): $mod = ($i % 2 );++$i;?><div class="recommend-item" onclick="javascript:window.location.href='<?php echo U('Lists/newHouseDetail',array('id'=>$house['id']));?>'">
            <div class="recommend-item-img">
                <img src="<?php echo ((isset($house["primaryImage"]) && ($house["primaryImage"] !== ""))?($house["primaryImage"]):'/public/no_photo.png'); ?>" alt="" />
            </div>
            <div class="houseInfo">
                <span class="title"><?php echo ($house["name"]); ?></span>
                <div class="attr">
                    <div class="left">
                        <span><?php echo ($house["area"]); ?></span>
                        <span>
                            <?php if(!empty($house['minHouseRoom']) && !empty($house['maxHouseRoom'])): if($house['minHouseRoom'] != $house['maxHouseRoom']){ echo $house['minHouseRoom'].'-'.$house['maxHouseRoom'].'室'; } else{ echo $house['maxHouseRoom'].'室'; } endif; ?>
                            <?php if((!empty($house['minHouseRoom']) || !empty($house['maxHouseRoom'])) && (!empty($house['minSquare']) || !empty($house['maxSquare']))): ?>|<?php endif; ?>
                            <?php if(!empty($house['minSquare']) && !empty($house['maxSquare'])): if($house['minSquare'] != $house['maxSquare']){ echo $house['minSquare'].'-'.$house['maxSquare'].'㎡'; } else { echo $house['maxSquare'].'㎡'; } endif; ?>
                            <?php if(empty($house['minHouseRoom']) && empty($house['maxHouseRoom']) && empty($house['minSquare']) && empty($house['maxSquare'])): ?>敬请关注<?php endif; ?>
                        </span>
                        
                    </div>
                    <div class="right">
                        <span style="flex-direction: row">
                            <span class="money">
                                <?php if($house['minTotalPrice'] > 0): echo renderMoney($house['minTotalPrice']); endif; ?>
                                <?php if(!($house['minTotalPrice'] > 0) && $house['minPrice']): echo renderMoney($house['minPrice']); endif; ?>
                            </span>
                            <span>
                                <?php if($house['minTotalPrice'] > 0): ?>万元/套起<?php endif; ?>
                                <?php if(!($house['minTotalPrice'] > 0) && $house['minPrice']): ?>元/㎡起<?php endif; ?>
                            </span>
                        </span>
                    </div>
                </div>
                <?php $classNames = array(1=>'first', 2=> 'second', 0=> 'third');?>
                <div class="label">
                    <span class="first"><?php echo ($house["stateCaption"]); ?></span>
                    <span class="second"><?php echo ($house["typeCaption"]); ?></span>
                <?php if(is_array($house['tags'])): foreach($house['tags'] as $key=>$tag): ?><span class="<?php echo ($classNames[$key%3]); ?>"><?php echo ($tagNames[$tag]); ?></span><?php endforeach; endif; ?>
                </div>
            </div>
        </div><?php endforeach; endif; else: echo "" ;endif; endif; ?>