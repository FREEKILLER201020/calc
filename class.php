<?php
class Event
{
    public $id;
    public $date;
    public $name;

    public function __construct($i,$d,$n)
    {
      $this->id=$i;
      $this->date=$d;
      $this->name=$n;
    }
}
class Event_web
{
    public $дата;
    public $название;

    public function __construct($d,$n)
    {
      $this->дата=$d;
      $this->название=$n;
    }
}
class Purchase
{
    public $id;
    public $name;
    public $price;
    public $quantity;
    public $total;
    public $member;

    public function __construct($i,$n,$p,$q,$t,$m)
    {
      $this->id=$i;
      $this->name=$n;
      $this->price=$p;
      $this->quantity=$q;
      $this->total=$t;
      $this->member=$m;
    }
}
 ?>
