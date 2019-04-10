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

class Member
{
    public $id;
    public $name;

    public function __construct($i,$n)
    {
      $this->id=$i;
      $this->name=$n;
    }
}

class Member_total
{
    public $id;
    public $name;
    public $total=0;
    // public $cashback=0;

    public function __construct($i,$n)
    {
      $this->id=$i;
      $this->name=$n;
    }
}

class Participation
{
    public $event_id;
    public $member_id;

    public function __construct($i,$i2)
    {
      $this->event_id=$i;
      $this->member_id=$i2;
    }
}

class Participation_add
{
    public $event_id;
    public $member_id;

    public function __construct($i,$i2)
    {
      $this->event_id=$i;
      $this->member_id=$i2;
    }
}
 ?>
