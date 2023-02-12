#! /usr/bin/env bash

if [ ! $1 ]
then
    echo "Usage: ./testCreator [PATH]"
    echo "For example: ./testCreator Config/Myconf/Config.php"
    exit
fi

PATHFILE=$1

create_dir_if_do_not_exist (){
    if [ ! -d $1 ]
    then
        mkdir $1
    fi

}
 
LIST=($(echo "$PATHFILE" | sed "s/\// /g"))
PATH_C=""
OPERATE=$(( ${#LIST[*]} -1 ))
CLASS=$( echo ${LIST[-1]} | sed "s/.php//g" )
FILENAME=($( echo $1 | sed "s/\// /g" ))
NAMESPACE=($( echo $1 | sed "s/\// /g" | sed "s/${FILENAME[-1]}//g" | sed "s/ /\\\/g" | sed s/.$//) )

for (( I=0; I<  $OPERATE; I++ ))
do
    if [ $I -eq 0 ]
    then
        PATH_C=${LIST[$I]}/
    else
        PATH_C=$PATH_C${LIST[$I]}/
    fi

    create_dir_if_do_not_exist $PATH_C
done

if [ ! -f $1 ]
then
    touch "$1"
fi

echo "<?php 

namespace $NAMESPACE;

use PHPUnit\Framework\TestCase;

class $CLASS extends TestCase
{

}
" > $1



