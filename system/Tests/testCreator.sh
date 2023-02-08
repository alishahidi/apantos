#! /usr/bin/env bash

if [ ! $1 ]
then
    echo "Usage: testCreator [PATH]"
    exit
fi

PATHFILE=$1
# PATHFILEWITHOUTTYPE=$PATHFILE | sed "s/.php//g"

# echo $PATHFILEWITHOUTTYPE


create_dir_if_do_not_exist (){
    if [ ! -d $1 ]
    then
        mkdir $1
    fi

}
 
LIST=($(echo "$PATHFILE" | sed "s/\// /g"))
PATH_C=""
OPERATE=$(( ${#LIST[*]} -1 ))
CLASS=${LIST[${#LIST[@]} - 1]} 
# echo $1 | sed -e "s/$CLASS//g"

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
    touch "$1.php"
fi

echo "
<?php 
use PHPUnit\Framework\TestCase;

class $CLASS extends TestCase
{

}
" > $1.php




