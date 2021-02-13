<!DOCTYPE html>
<html>

<head>
    <title>Chuiso y el Excel Legendario - El videojuego</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />



    <link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>



    <link rel="prefetch" href="static/music.mp3" />
    <link rel="prefetch" href="static/item.mp3" />
    <link rel="prefetch" href="static/ded.mp3" />
    <link rel="prefetch" href="static/door.mp3" />''
    <link rel="prefetch" href="static/player.png" />
    <link rel="prefetch" href="static/enemy.png" />
    <link rel="prefetch" href="static/item.png" />
    <link rel="prefetch" href="static/floor.png" />
    <link rel="prefetch" href="static/door_closed.png" />
    <link rel="prefetch" href="static/door_open.png" />
    <link rel="prefetch" href="static/logo.png" />
    <link rel="prefetch" href="static/stairs.png" />


            
    <meta property="og:image" content="https://vivirenremoto.github.io/chuiso/static/social.png">
    <meta property="og:image:secure_url" content="https://vivirenremoto.github.io/chuiso/static/social.png">
    <meta property="og:image:alt" content="Chuiso y el Excel Legendario - El videojuego">
    <meta property="og:image:type" content="image/png">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Chuiso y el Excel Legendario - El videojuego">
    <meta name="twitter:image" content="https://vivirenremoto.github.io/chuiso/static/social.png">


</head>

<body>



    <style>
        * {
            font-family: 'VT323', monospace;
            text-transform: uppercase;
        }

        body {
            background: black;
        }

        table {
            background: black;
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
        }

        td {
            background-image: url('static/floor.png');
            background-repeat: repeat-x;
            background-color: #0000F8;/*white;*/
            background-position: left bottom;
            width: 25%;
            height: 25%;
            vertical-align: top;
        }

        .obj {
            position: fixed;
            width: 100%;
            height: 100px;
            background-size: 100%;
            background-repeat: no-repeat;
            background-position: center center;
        }

        .item {
            background-image: url('static/item.png');
        }

        .player {
            background-image: url('static/player.png');
            z-index: 2;
        }

        .enemy {
            background-image: url('static/enemy.png');
            z-index: 3;
        }

        .stairs {
            background-image: url('static/stairs.png');
        }

        .door {
            background-image: url('static/door_closed.png');
        }

        .invert{
            filter: invert(100%);
        }

        button {
            border: 5px white solid;
            color: white;
            background: black;
            padding: 5px 30px;
            display: inline-block;
            font-size: 30px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            text-transform: uppercase;
            width:250px;
            margin-bottom:15px;
        }
        

        @media screen and (min-width: 480px) {
            table {
                width: 480px;
                left: 50%;
                right: 50%;
                margin-left: -240px;
            }

        }
    </style>


    <div id="intro" style="padding:20px;font-size:50px;text-align:center;color:white;position:fixed;left:0;right:0;top:0;bottom:0;background:black;">
        CHUISO Y EL EXCEL
        <br><br>
        <button onclick="startGame()">EMPEZAR</button>
    </div>


    <table id="game" style="display:none">
    </table>


    <div id="result1" style="padding:20px;font-size:50px;text-align:center;color:white;position:fixed;left:0;right:0;top:0;bottom:0;display:none;background:black;">

    <span style="color:#00ff00;">ENHORABUENA</span>

    <br>
    HAS RECUPERADO TODOS LOS FRAGMENTOS

    <br><br>

    <img src="static/item.png" width="200" height="200">


    <br><br>

    <button onclick="$('#result1').hide();$('#result2').show();">    
    ABRIR EXCEL
    </button>


    </div>


    
    <div id="result2" style="padding:20px;font-size:50px;text-align:center;color:white;position:fixed;left:0;right:0;top:0;bottom:0;display:none;background:black;">


    NO EXISTE UNA FÓRMULA MÁGICA PARA GANAR DINERO EN INTERNET, PERO SI ERES CONSTANTE Y TRABAJAS DURO EN TEAMPLATINO PODRÁS APRENDER A DAR TUS PRIMEROS PASOS


    <br><br>    
    <img src="static/logo.png">


    <br>
    <!--<button onclick="location.reload();">¿REPETIR?</button>-->
    <br>
        <button onclick="share()">COMPARTIR</button>
        <br>
        <button onclick="download()">DESCARGAR PDF</button>


    </div>


    <audio id="music" loop>
        <source src="static/music.mp3" type="audio/mp3">
    </audio>

<audio id="sound_item">
    <source src="static/item.mp3" type="audio/mp3">
</audio>

<audio id="sound_ded">
    <source src="static/ded.mp3" type="audio/mp3">
</audio>

<audio id="sound_door">
    <source src="static/door.mp3" type="audio/mp3">
</audio>


    <script>
        var current_stage = 1;
        var screen_height = $(document).height();
        var screen_width = $(document).width();
        var td_height;
        var td_width;
        var player_col;
        var player_row;
        var item_col;
        var item_row;
        var door_col;
        var door_row;
        var timer_enemy;
        var got_item = false;
        var is_ded = false;
        var is_moving = false;
     

        var stage;


        var table_x_start;
        var table_x_end;

        var timer_move;
            
        var max_col;
        var max_row;



        var music_playing = false;

        var music = document.getElementById('music');
        music.volume = 0.5;

        var sound_item = document.getElementById('sound_item');
        sound_item.volume = 1;

        var sound_ded = document.getElementById('sound_ded');
        sound_ded.volume = 1;

        var sound_door = document.getElementById('sound_door');
        sound_door.volume = 1;



        var stages = [

       
            [
                ['P', '', '', ''],
                ['I', '', 'S', ''],
                ['', '', 'S', ''],
                ['', '', 'S', 'D'],
            ],

            [
                ['', 'I', '', ''],
                ['', '', '', 'S'],
                ['S', '', 'E', ''],
                ['S', '', 'P', 'D']
            ],

            [
                ['', '', '', 'I'],
                ['S', '', '', 'E'],
                ['S', 'E', '', ''],
                ['S', '', 'P', 'D'],
            ],

            [
                ['', '', 'D', ''],
                ['', 'I', 'E', 'S'],
                ['S', '', '', 'S'],
                ['S', '', 'P', ''],
            ],

            [
                ['E', '', 'D', ''],
                ['I', '', 'E', 'S'],
                ['S', '', '', 'S'],
                ['', '', 'P', 'S'],
            ]

        ];



        function drawStage(){

            got_item = false;
            is_ded = false;

            $('table').html('');

            stage = stages[current_stage-1];


            stage.forEach(function (currentValue, row, array) {

                var html = '<tr>';

                stage[row].forEach(function (currentValue2, col, array2) {

                    var row_value = stage[row][col];
                    var class_name;
                    var html_aux = '';

                    if (row_value == 'I') {
                        class_name = 'item';
                        item_row = row;
                        item_col = col;
                        html_aux = '<div style="font-size:20px;color: white;text-align: center;">parte-' + current_stage + '.xls</div>';
                        
                    } else if (row_value == 'E') {
                        class_name = 'enemy';
                    } else if (row_value == 'P') {
                        class_name = 'player';
                        player_row = row;
                        player_col = col;


                    } else if (row_value == 'S') {
                        class_name = 'stairs';
                    } else if (row_value == 'D') {
                        class_name = 'door';
                        door_row = row;
                        door_col = col;
                    } else {
                        class_name = '';
                    }

                    //onclick="selectCell('+row+','+col+')"
                    html += '<td data-row="'+row+'" data-col="'+col+'"><div class="obj ' + class_name + '" >' + html_aux + '</div></td>';
                });

                html += '</tr>';

                $('#game').append(html);





            });


                
        



            td_height = $('td:first').height() - 54;
            td_width = $('td:first').width();
            $('.obj').css('width', td_width).css('height', td_height);


            table_x_start = parseInt($('table').css('left'));
            table_x_end = table_x_start + parseInt($('#game').css('width'));









            $("td").on("mousedown touchstart", function (e) {


                var obj = $(this);


                selectCell( $(obj).data('row'), $(obj).data('col') );

                

                timer_move = setInterval(function () {

                    //$(obj).click();

                    selectCell( $(obj).data('row'), $(obj).data('col') );



                }, 500);
            });


            $("td").on("mouseup touchend", function (e) {
                clearInterval(timer_move);
            });




            

                
            if (screen_width > 480) {
                table_x_start -= 240;
                table_x_end -= 240;
            }




            var enemies_pos = ['right','left','right','left'];


            clearInterval(timer_enemy);

            timer_enemy = setInterval(function () {
                $('.enemy').each(function (index) {

                    var enemy_x = parseInt($(this).css('left'));
                    var enemy_y = parseInt($(this).css('top'));


                    //console.log(enemy_pos + '----' + enemy_x + ' , ' + table_x_end);


                    if (enemy_x <= table_x_start) {
                        enemies_pos[index] = 'right';
                    } else if ((enemy_x + td_width) >= table_x_end) {
                        enemies_pos[index] = 'left';
                    }

                    //if (enemies_pos[index]) {


                        if (enemies_pos[index] == 'left') {
                            enemy_x -= 1;
                        } else {
                            enemy_x += 1;
                        }



                        $(this).css('left', enemy_x);

                    //}


                    if( !is_ded ){
                        var player_x = parseInt($('.player').css('left'));
                        var player_y = parseInt($('.player').css('top'));
                        var player_width = parseInt($('.player').css('width')) - 20;
                        var player_height  = parseInt($('.player').css('height')) - 20;

                        var diff_x = (player_x - enemy_x);
                        if (diff_x < 0) diff_x *= -1;

                        var diff_y = (player_y - enemy_y);
                        if (diff_y < 0) diff_y *= -1;


                        if (diff_x < player_width) {
                            if (diff_y < player_height) {


                                
                                is_ded = true;

                                $('.player').addClass('invert');

                                sound_ded.play();

                                $('.player').animate({
                                    'top': '-=100',
                                    'opacity': '0',
                                }, 1000, function(){
                                    setTimeout(function(){
                                        drawStage();
                                    }, 200);
                                });

                            }
                        }
                    }


                });


            }, 10);




            max_col = $('table tr:first td').length;
            max_row = $('table tr').length;





        }


        
        function startGame(){
            $('#intro').hide();
            $('#game').show();
            drawStage();






            $("body").keydown(function (e) {
                if (e.keyCode == 37) {
                    moveLeft();
                } else if (e.keyCode == 39) {
                    moveRight();
                } else if (e.keyCode == 38) {
                    moveUp();
                } else if (e.keyCode == 40) {
                    moveDown();
                }
            });




        }
        



        function updatePos(){

            //console.log(player_col+'---'+player_row);
           
           if( !is_moving ){
                is_moving = true;
           

            

                $('.player').animate({
                    'top': $('table tr:eq('+(player_row)+') td:first').offset().top+'px',
                    'left': $('table tr:eq('+(player_row)+') td:eq('+(player_col)+')').offset().left+'px',
                }, 100, function(){
                    is_moving = false;
                });


                if( !got_item && item_col == player_col && item_row == player_row ){
                    got_item = true;
                    $('.item').hide();
                    $('.door').css('background-image', "url('static/door_open.png')");

                    
                    sound_door.play();




                }

                if( got_item && door_col == player_col && door_row == player_row ){
                    
                
                    
                    current_stage++;


                    sound_item.play();
                    
                    if(current_stage > stages.length ){
                        $('#game').hide();
                        $('#result1').show();
                    }else{


                        drawStage();
                    }

                    

                
                }

            }
        }

        function selectCell(row, col){


            //alert(player_row+'--'+player_col);
            /*
            console.log('col'+player_col+'/'+col+'---row'+player_row+'/'+row);
                if( player_col == col && player_row == row ){
                    alert('ddd');
                    return;
                }
            */


            if( player_col == col ){
            
                /*
                if( player_row == (row+1) ){
                    moveUp();
                }else if( player_row == (row-1) ){
                    moveDown();
                }
                */
               if( player_row < row  ){
                moveDown();
               }else if( player_row > row  ){
                moveUp();
               }
            }else if( player_row == row ){




                /*
                if( player_col == (col+1) ){
                    moveLeft();
                }else if( player_col == (col-1) ){
                    moveRight();
                }
                */
                
               if( player_col < col  ){
                moveRight();
               }else if( player_col > col  ){
                moveLeft();
               }
            }


        }

        function moveLeft(){
            if( player_col > 0 ){
                player_col--;
                updatePos();
            }
        }

        function moveRight(){
            if( player_col < (max_col-1) ){
                player_col++;
                updatePos();
            }
        }
        function moveUp(){

            

            if( stage[player_row][player_col] == 'S' && player_row > 0 ){
                player_row--;
                updatePos();
            }
        }

        function moveDown(){
            if( player_row < (max_row-1) && stage[player_row+1][player_col] == 'S' ){
                player_row++;
                updatePos();
            }
        }

        function share() {
            music.pause();
            window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent(document.title) + '&tw_p=tweetbutton&url=' + document.location.href);
        }

        function download(){
            music.pause();
            window.open('http://google.es');
        }


        $("body").click(function(){
            if (!music_playing) {
                music_playing = true;
                music.play();
            }
        });



    </script>

</body>

</html>