<!DOCTYPE html>
<?php
    require_once('session.php');
    if(isset($_SESSION)){
        header("refresh:8;url=sys.php");
    } else{
        header("refresh:8;url=index.php");
    }
?>
<html>
    <head>
        <title> Ops! Erro - 404 Page Not Found</title>
        <meta charset="utf-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />    
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="shortcut icon" href="img/icon.png" type="image/x-icon" />
        <!-- jQuery Ajax - Google -->
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <style>
            body{
                background-color: #060623;
            }
            .circle { 
                border-radius:50%;
                position:absolute;
                overflow: hidden;
            }
            #container {
                width: 100vw;
                height: 100vh;
                position:relative;
            }
            .bouncer{
                position: absolute;
                width: 51px;
                height: 51px;
            }
        </style>
    </head>
    <body>
        <div id="container">
            <div id="aviso" align="center"></br></br></br></br>
                <h4 class="badge badge-danger justify-content-center" style="text-color: white;">OPS! Caímos no vazio...</h4></br>
                <h6 class="badge badge-light justify-content-center">Um instante, vamos reacoplá-lo ao nosso sistema!</h6>
            </div>
        </div>
        <div class="bouncer" id="bouncer" title="astronauta">
            <img src="img/astronauta.png"/>
        </div>
    </body>
    <script>
        function getRandomColor() {
            var colours = ["#ffffff", "#d5d5d5", "#f1f1f1", "#eeeee1", "#b1b5bb"];
            return colours[Math.floor(Math.random() * 4)]
        }

        var maxDiam = 8;
        var circleNum = 440;
        var container = $("#container");
        var containerWidth = container.width();
        var containerHeight = container.height();

        $(document).ready(function() {
            for (var i = 0; i < circleNum; i++) {
                var newCircle = $("<div />")
                var d = Math.floor(Math.random() * maxDiam);
                newCircle.addClass("circle");

                newCircle.css({
                    width: d,
                    height: d,
                    left: Math.random() * Math.max(containerWidth - d, 0),
                    top: Math.random() * Math.max(containerHeight - d, 0),
                    backgroundColor: getRandomColor()
                });
                container.append(newCircle);
            }
        });
        var windowWidth = window.innerWidth;
        var windowHeight = window.innerHeight;
        var screenWidth = screen.width;
        var screenHeight = screen.height;
        $(function() {
            var minSpeed = .03;
            var maxSpeed = .06;
            var varSpeed = .005;

            function startBounce(element) {
                var container = element.parent();
                console.log('parent: ' + element.parent());
                var width = container.innerWidth() - element.outerWidth();
                var height = container.innerHeight() - element.outerHeight();
                var vertSpeed = ((Math.random() * (maxSpeed - minSpeed)) + minSpeed);
                var horzSpeed = ((Math.random() * (maxSpeed - minSpeed)) + minSpeed);
                bounce(element, vertSpeed, height, 'top');
                bounce(element, horzSpeed, width, 'right');
            }

            function bounce(element, speed, max, dir) {
                speed += ((Math.random() * varSpeed) - (varSpeed / 2));
                speed = speed < minSpeed ? minSpeed : (speed > maxSpeed ? maxSpeed : speed)
                var time = max / speed;
                var position = element.position();
                if (position[dir] < 2) {
                    target = Math.random()*max;
                } else {
                    target = Math.random()*speed;
                }
                var style = {
                    queue: false
                };
                style[dir] = target;
                element.animate(style, {
                    duration: time,
                    queue: false,
                    easing: "linear",
                    complete: function() {
                        bounce(element, time, max, dir);
                    }
                });
            }
            startBounce($('#bouncer'));
        });
    </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</html>