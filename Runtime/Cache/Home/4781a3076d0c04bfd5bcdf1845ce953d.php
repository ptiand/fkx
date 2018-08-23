<?php if (!defined('THINK_PATH')) exit(); if(empty($houses)): ?><div class="message">
        <span>暂无精选房源！</span>
    </div>
    <?php else: ?>
    <?php if(is_array($houses)): $i = 0; $__LIST__ = $houses;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$house): $mod = ($i % 2 );++$i;?><div class="recommend-item"  onclick="javascript:window.location.href='<?php echo U('RentalHouse/detail',array('id'=>$house['id']));?>'">
            <div class="recommend-item-img">
                <img src="<?php echo ((isset($house["primaryImage"]) && ($house["primaryImage"] !== ""))?($house["primaryImage"]):'/public/no_photo.png'); ?>" alt="" />
            </div>
            <div class="houseInfo">
                <span class="title"><?php echo ($house["name"]); ?></span>
                <div class="attr">
                    <div class="left">
                        <span><?php echo ($house["area"]); ?></span>
                        <span><?php echo ($house["houseRoom"]); ?>室<?php echo ($house["livingRoom"]); ?>厅<?php if($house['restRoom'] > 0): echo ($house["restRoom"]); ?>卫<?php endif; ?>
                        | <?php echo renderNumber($house['square'],0);?>㎡</span>
                    </div>
                    <div class="right">
                        <span style="flex-direction: row">
                            <span class="money"><?php echo renderMoney($house['price']);?></span>
                            <span>元/月</span>
                        </span>
                    </div>
                </div>
                <?php $classNames = array(1=>'first', 2=> 'second', 0=> 'third');?>
                <div class="label">
                    <?php if($house['source'] == $Source['Personal']): if(!empty($house['stateCaption'])): ?><span class="first"><?php echo ($house["stateCaption"]); ?></span><?php endif; ?>
                        <?php if(!empty($house['typeCaption'])): ?><span class="second"><?php echo ($house["typeCaption"]); ?></span><?php endif; endif; ?>
                    <?php if(is_array($house['tags'])): foreach($house['tags'] as $key=>$tag): ?><span class="<?php echo ($classNames[$key%3]); ?>"><?php echo ($tagNames[$tag]); ?></span><?php endforeach; endif; ?>
                </div>
            </div>
        </div><?php endforeach; endif; else: echo "" ;endif; endif; ?>