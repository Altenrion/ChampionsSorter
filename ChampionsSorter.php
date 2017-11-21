<?php


class ChampionsSorter
{
    private $waitList;
    private $fightMode;

    private $schedule;

    public $repeats=0;

    public function __construct(ChampionsWaitList $list, $lastFight=false)
    {
        $this->waitList = $list;
        $this->fightMode = $lastFight;
    }


    public function sort()
    {
        while(count($this->waitList->getAmount())>0){

            $first = $this->waitList->getChampion();

            tryAnother:
            $second = $this->waitList->getChampion();
            try {
                $pair = ChampionsPair::init($first, $second, $this->fightMode);
            } catch (Exception $e) {
                $this->waitList->returnChampion($second);

                $this->repeats++;

//                echo $e->getMessage()."\n";
//                echo "Same: $second->club ,";
                goto tryAnother;
            }
            $this->schedule[] = $pair->getPair();

            if($this->waitList->getAmount() <= 3 ){
                $this->schedule["LastChance"] = $this->waitList->rest();
                break;
            }
        }
        return $this->schedule;
    }
}

class ChampionsWaitList
{

    private $list=[];

    public function __construct(){}

    /**
     * @return Champion
     */
    public function getChampion(){
        shuffle($this->list);

        $champion = array_shift($this->list);
        return $champion;
    }

    public function insertChampion(Champion $champion){
        shuffle($this->list);

        $this->list[] = $champion;
    }

    public function returnChampion(Champion $champion){
        $this->list[] = $champion;
    }

    public function getAmount(){
        return count($this->list);
    }

    public function rest(){
        return $this->list;
    }
}



class ChampionsPair
{
    private $pair;
    private $lastFight;

    private $first;
    private $second;

    private function __construct(Champion $champion_one, Champion $champion_two, $lastFight=false)
    {

        $this->first = $champion_one;
        $this->second =$champion_two;

        $this->lastFight = $lastFight;

        if(!$this->validate()){
            throw new Exception("Champions not match");
        }

        $this->create();
    }

    private function validate(){
        if(!$this->lastFight){
            if($this->first->club == $this->second->club)
                return false;

//            if(abs(($this->first->weight - $this->second->weight))> 8)
//                return false;
        }

        return true;
    }

    private function create(){
        $this->pair= array($this->first, $this->second);
    }

    public function getPair(){
        return $this->pair;
    }

    public static function init(Champion $champion_one, Champion $champion_two, $lastFight=false){
        return new static($champion_one, $champion_two, $lastFight);
    }
}

class Champion
{
    public function __construct(array $champion)
    {
        $this->name = $champion['name'];
        $this->club = $champion['club'];
        $this->age = $champion['age'];
        $this->weight = $champion['weight'];
    }
}
