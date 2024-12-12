<?php

namespace CodedMonkey\Conductor\Doctrine\Entity;

enum PackageFetchStrategy: string
{
    case Mirror = 'mirror';
    case Vcs = 'vcs';
}
