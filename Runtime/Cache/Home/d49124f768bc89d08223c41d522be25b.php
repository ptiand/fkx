<?php if (!defined('THINK_PATH')) exit(); if(is_array($houseList)): foreach($houseList as $key=>$house): ?><div class="recommend-item">
        <?php $modifyUrl=U('Publish/'.$house["publishPath"], array("id"=>$house["id"])); ?>
        <div class="main-title to-publish" onclick="javascript: window.location.href='<?php echo ($modifyUrl); ?>'">
            <div><b><?php echo ($house['publishTypeCaption']); ?></b><span>修改: <?php echo date('Y-m-d H:i', $house['updateTime'])?></span></div>
            <span class="gt">修改&nbsp;&nbsp;<img src="/public/Home/images/icon_gt.png" width="6" height="12" /></span>
        </div>
        <?php $detailUrl=U($house['detailPath'].'/detail', array('id'=>$house['id'])) ?>
        <div class="main-content" onclick="javascript: window.location.href='<?php echo ($detailUrl); ?>'">
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
                            <span class="money"><?php echo renderMoney($house['priceCaption'], 0);?></span>
                            <span><?php echo ($house['priceCaptionUnit']); ?></span>
                        </span>
                    </div>
                </div>
                <div class="label">
                    <span class="first"><?php echo ($house["stateCaption"]); ?></span>
                    <span class="second"><?php echo ($house["typeCaption"]); ?></span>
                    <?php $classMap=array('1'=>'first', '2'=>'second', '0'=>'third',)?>
                    <?php if(is_array($house['tagList'])): foreach($house['tagList'] as $key=>$tag): ?><span class="<?php echo ($classMap[$key%3]); ?>"><?php echo ($tag); ?></span><?php endforeach; endif; ?>
                </div>
            </div>
        </div>
    </div><?php endforeach; endif; ?>