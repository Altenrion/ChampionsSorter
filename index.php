<?php

include_once "ChampionsSorter.php";

$names = ["Сергей", "Илья", "Паша", "Антон", "Алексей", "Захар", "Майкл", "Питер", "Жамшут"];
$clubs = ["Мураейник", "СК бурундуки", "Надежда Самарканда", "Бегущая антилопа", "Горцующая надежда"];

$data = [];
$total_amount = rand(9,37);

for ($i = 1; $i <= $total_amount; ++$i){
    $data[$i]['name'] = $names[array_rand($names)];
    $data[$i]['club'] = $clubs[array_rand($clubs)];
    $data[$i]['age'] = rand(18,35);
    $data[$i]['weight'] = rand(64,99);
}

$list = new ChampionsWaitList();

foreach ($data as $champion) {
    $list->insertChampion(new Champion($champion));
}

$sorter = new ChampionsSorter($list);
$battleSchedule = $sorter->sort();


echo "<style <style type=\"text/css\">
    .main-block {
        margin: 30px auto;
        width: 80%;
        height: 90%;
        background: #fffae8;
        border-radius: 10px;
        padding: 20px;
        overflow: hidden;
        border: 1px solid #cecece;
    }
    .left-block{
        width: 350px;
        height: 100%;
        float: left;
        overflow-y: scroll;

    } 
    .right-block{
        width: 65%;
        height: 100%;
        float: right;
        overflow-y: scroll;
        margin-top: 125px;
    }
    span.avatar {
        float: left;
    }
    .avatar img{
        width: 60px;
        padding:0 10px 0 0;
        float: left;    
    }
    .champion-card {
        height: 60px;
        border-bottom: 1px solid #ababab;
        padding: 10px;
        margin-bottom: 10px;
        background: #baeaff;
    }
    .champion-card span{
        display: inline-block;
        font-family: sans-serif;
    }
    span.club {
        font-size: 23px;
    }
    span.common-data {
        font-size: 15px;
    }
    .block-data {
        height: 70px;
        background: #67d6fb;
        /* border: 1px solid; */
        width: 28%;
        float: left;
        margin: 8px;
        padding: 8px;
    }
    .pars {
        background: red;
        height: fit-content;
        margin-top: 110px;
    }
    .champion-card.compare {
        background: #c2d8c2;
        width: 325px;
        float: left;
        margin-right: 10px;
        margin-left: 10px;
    }
   .pairs .champion-card:nth-child(3) {
    margin-top:20px;
   }
   .pairs .champion-card:nth-child(2) {
        float: right;
        margin-right: 10px;
    }
    .pairs {
        margin-bottom: 20px;
        height: 100px;
    }
    .list {
        /* margin-top: 140px; */
        margin-bottom: 200px;
    }
    body .main-block .pairs .champion-card:after {
        content: ' ';
        display: block;
        width: 106%;
        background: #baeaff;
        height: 43px;
        margin-top: -16px;
        margin-left: -10px;
    }
    body .main-block .pairs:not(:first-child).ungrouped .champion-card{
        background: darkcyan;
    }
    .header {
        position: absolute;
        left: 37%;
        width: 56%;
        top: 45px;
        text-align: center;
        font-size: 30px;
        font-family: sans-serif;
    }
    .header .block-data{
        padding-top:20px;
    }
    .pairs:not(ungrouped):after {
        content: ' ';
        width: 12%;
        height: 12px;
        background: #1eb1fc;
        /* position: absolute; */
        margin-left: 43%;
        margin-top: -52px;
        /* z-index: 0; */
        display: inline-block;
    }
</style>";
?>

<div class='main-block' >
    <div class="left-block">
        <? foreach ($data as $champion): ?>
        <div class="champion-card">
            <span class="avatar"><img src="img/avatar.png" alt=""></span>
            <div class="profile"><span class="club"><?=$champion['club'] ?></span></div>
            <span class="common-data"><?=$champion['name'].', '.$champion['age'] ." лет, ".$champion['weight']." кг" ?></span>
        </div>

        <? endforeach; ?>
    </div>
    <div class="right-block">
        <div class="header">
            <div class="block-data">Всего : <?= count($data)?></div>
            <div class="block-data">Повторов : <?=$sorter->repeats ?></div>
            <div class="block-data">Пар : <?= count($battleSchedule) ?></div>
        </div>
        <div class="list">
            <? foreach ($battleSchedule as $k =>$pair) :?>
                <div class="pairs <?= ($k=="LastChance" ? "ungrouped":"") ?>">
                    <? foreach ($pair as  $champion) :?>
                        <div class="champion-card compare ">
                            <span class="avatar"><img src="img/avatar.png" alt=""></span>
                            <div class="profile"><span class="club"><?=$champion->club ?></span></div>
                            <span class="common-data"><?=$champion->name.', '.$champion->age ." лет, ".$champion->weight." кг" ?></span>
                        </div>
                    <?endforeach;?>
                </div>
            <?endforeach;?>
        </div>
    </div>
</div>