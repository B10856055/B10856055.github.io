<?php
require "vendor\autoload.php";
use Google\Cloud\Vision\VisionClient;
require "PHP-Translate-using-Google-Translator-API-master\\vendor\autoload.php";
use \Statickidz\GoogleTranslate;
if(isset($_FILES['image']['tmp_name'])){
    session_start();

    $vision = new VisionClient(['keyFile' => json_decode(file_get_contents("key.json"),true)]);

    $familyPhotoResource = fopen($_FILES['image']['tmp_name'],'r');

    $image = $vision->image($familyPhotoResource,['FACE_DETECTION','WEB_DETECTION','LABEL_DETECTION']);
    $result = $vision->annotate($image);
    $F1_tmp_name = $_FILES['image']['tmp_name'];
    if(is_dir("feed") == "")
        {
            mkdir("feed");
        }
    $ImageName = $_FILES['image']['name'];
    $Imagetoken = random_int(1111111,999999999);
    if($result) {
        move_uploaded_file($F1_tmp_name, "feed/$Imagetoken.jpg"); 
    } else{
        header("location:detection.php");
        die();
    }
    $faces = $result->faces();
    $logos = $result->logos();
    $labels = $result->labels();
    $text = $result->text();
    $fullText = $result->fullText();
    $properties = $result->imageProperties();
    $cropHints = $result->cropHints();
    $web = $result->web();
    $safeSearch = $result->safeSearch();
    $landmarks = $result->landmarks();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fall in 浪</title>
    <link rel="icon" href="images/icon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="indexstyle.css">
    <link rel="stylesheet" href="detection.css">
</head>
<body>
<header id="head">
    <nav class="menu" id="menu">
        <a id="menubtn" class="toggle-nav" href="#">☰</a>
        <div class="menu-logo"><a href="index.html"><img src="images/icon.png" alt="" style="height: 50px;"></a></div>
        <ul id="menuUl" class="menuactive">
            <li><a href="index.html">首頁</a></li>
            <li><a href="detection.php" style="color: #70e6ec;">辨識</a></li>
        </ul>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script>
            document.getElementById('menubtn').addEventListener('click', function (e) {
                $('#menuUl').toggleClass('menuactive');
                $('#head').toggleClass('menuactive2');
            })
            var bodyClass = document.body.classList,
                lastScrollY = 100;
            window.addEventListener('scroll', function(){
            var st = this.scrollY;
            if( st < lastScrollY) {
                bodyClass.remove('hideUp');
            }
            else if(st==100){
                bodyClass.remove('hideUp');
            }
            else if(st>100){
                bodyClass.add('hideUp');
            }
            lastScrollY = st;
            });
        </script>
    </nav>
</header>

<div class="content">
    <div id="sidebar_top">
        <form action="detection.php" method="post" enctype="multipart/form-data" id="upload">
            上傳檔案：<input type="file" name="image" accept="image/*">
            <button type="submit">送出</button>
        </form>
    </div>
    <div id="sidebar_left">
        <div id="sidebar_left_img">
            <?php
                if(isset($Imagetoken)){
                    echo '<img id="preview_progressbarTW_img" src="feed/'.$Imagetoken.'.jpg">';
                }
                else{
                    echo '<img id="preview_progressbarTW_img" src="images\0.jpg">';
                }
            ?>
        </div>
    </div>
    <div id="sidebar_right">
        <?php
            if(isset($Imagetoken)){
                    echo '<h1 style="color:white;text-align: left;">辨識結果：</h1>';
                    foreach ($labels as $key =>$label):
                        $garbage = array('Fluid');
                        if(in_array($label->info()['description'],$garbage)==FALSE){
                            echo '<h3>';
                            echo ucfirst($label->info()['description']);
                            echo '&emsp;準確率：'; 
                            echo number_format($label->info()['score']*100 , 2);
                            echo '%&emsp;';
                            $source = 'en';
                            $target = 'zh-TW';
                            $text = ucfirst($label->info()['description']);
                            $trans = new GoogleTranslate();
                            $result = $trans->translate($source, $target, $text);
                            echo '<a href="https://zh.wikipedia.org/wiki/'.$result.'" target="_blank"><button>介紹</button></a>';
                            echo '</h3>';
                        }
                    endforeach;
            }
            else{
                echo '<h1 style="color:white;text-align: left;">辨識結果：</h1>';
                echo '<h3>Anemone fish&emsp;準確率：%&emsp;<button>介紹</button></h3>';
                echo '<h3>Clownfish&emsp;準確率：%&emsp;<button>介紹</button></h3>';
                echo '<h3>Water&emsp;準確率：%&emsp;<button>介紹</button></h3>';
                echo '<h3>Underwater&emsp;準確率：%&emsp;<button>介紹</button></h3>';
                echo '<h3>Organism&emsp;準確率：%&emsp;<button>介紹</button></h3>';
                echo '<h3>Fin&emsp;準確率：%&emsp;<button>介紹</button></h3>';
                echo '<h3>Fish&emsp;準確率：%&emsp;<button>介紹</button></h3>';
                echo '<h3>Marine biology&emsp;準確率：%&emsp;<button>介紹</button></h3>';
                echo '<h3>Ray-finned fish&emsp;準確率：%&emsp;<button>介紹</button></h3>';
                echo '<h3>Tail&emsp;準確率：%&emsp;<button>介紹</button></h3>';
            }  
        ?>
    </div>
    
</div>
<div class="foo">
    <h2>Fall in 浪</h2>
</div>
</body>
</html>