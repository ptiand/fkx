<?php if (!defined('THINK_PATH')) exit(); if(is_array($houseList)): foreach($houseList as $key=>$house): ?><div class="recommend-item" onclick="javascript:window.location.href='<?php echo U('RentalHouse/detail', array('id'=>$house['id']));?>'">
        <div class="main-content">
            <div class="recommend-item-img">
                <img src="<?php echo ((isset($house["primaryImage"]) && ($house["primaryImage"] !== ""))?($house["primaryImage"]):'/public/no_photo.png'); ?>" alt="图片"/>
            </div>
            <div class="houseInfo">
                <span class="title"><?php echo ($house["name"]); ?></span>
                <div class="attr">
                    <div class="left">
                        <span><?php echo ($house["areaCaption"]); ?></span>
                        <span>
                            <?php
 $text=''; if($house['houseRoom']) $text .= $house['houseRoom'] . '室'; if($house['livingRoom']) $text .= $house['livingRoom'] . '厅'; if($house['restRoom']) $text .= $house['restRoom'] . '卫'; if($house['diningRoom']) $text .= $house['diningRoom'] . '厨'; if($text) $text .= ' |'; echo $text; ?>
                            <?php echo (round($house["square"])); ?>㎡
                        </span>
                    </div>
                    <div class="right">
                        <span style="flex-direction: row">
                            <span class="money"><?php echo renderMoney($house['price'], 0);?></span>
                            <span>元/月</span>
                        </span>
                    </div>
                </div>
                <div class="label">
                    <?php if($house['source'] == $Source['Personal']): if(!empty($house['stateCaption'])): ?><span class="first"><?php echo ($house["stateCaption"]); ?></span><?php endif; ?>
                        <?php if(!empty($house['typeCaption'])): ?><span class="second"><?php echo ($house["typeCaption"]); ?></span><?php endif; endif; ?>
                    <?php $classMap=array('1'=>'first', '2'=>'second', '0'=>'third',)?>
                    <?php if(is_array($house['tagList'])): foreach($house['tagList'] as $key=>$tag): ?><span class="block <?php echo ($classMap[$key%3]); ?>"><?php echo ($tag); ?></span><?php endforeach; endif; ?>
                </div>
            </div>
        </div>
        <?php if($house['source']==2 && $house['commission']): ?><div class="bottom-content">
                <span>佣金： <?php echo ($house["commission"]); ?></span>
            </div><?php endif; ?>
    </div><?php endforeach; endif; ?>