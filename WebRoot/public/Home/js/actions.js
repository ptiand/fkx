
            function controls() {
                var bgContainer = document.getElementById('container');
                var bg = document.getElementById('background');
                items = {
                    wid: 400,
                    hei: 680,
                    sec_1: 1000/61,
                    sec_2: 1000/35,
                    width: window.innerWidth,
                    height: window.innerHeight,
                    imgContainer: document.getElementsByClassName('bg-img')[0]
                };
                function lok() {
                    if(items.width===414){
                        items.wid = 455;
                        items.hei = 710;
                    }else if(items.width===375){
                        items.wid = 420;
                        items.hei = 750;
                    }
                }
                lok()
                function action() {
                    var firstAct = setInterval(function() {
                        items.wid--;
                        if (items.wid===items.width){
                            clearInterval(firstAct);
                        }
                        bg.style.width = items.wid; 
                    },items.sec_2);
                    var secondAct = setInterval(function() {
                        items.hei--;
                        if (items.hei===items.height){
                            clearInterval(secondAct);
                        }
                        bg.style.height = items.hei; 
                    },items.sec_1); 
                    bg.setAttribute('class','acter')
                };
                action();
                return items;
                bgContainer.style.width = items.width;
                bgContainer.style.height = items.height;       
            }
            controls();