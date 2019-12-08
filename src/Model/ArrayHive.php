<?php

namespace JohnFallis\Model;

use Doctrine\Common\Collections\ArrayCollection;

class ArrayHive extends ArrayCollection implements HiveCollection
{
    public function __construct()
    {
        // override parent constructor to mitigate collection pollution
    }
}
