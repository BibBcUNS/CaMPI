#uso backup <base>

# verificamos que exista el directorio para la BD
if [ ! -d "backups/$1" ]
then
    mkdir backups/$1
fi

# recupero el contador de backup y lo incremento en uno (modulo 5)

bkcount= $(cat backups/$1/count.txt)

if [ -z $bkcount ]
then
    bkcount=0
fi

bkcount=$(((bkcount+1)% 6))
echo $bkcount > backups/$1/count.txt


# elimino el direcotrio para la copia nro $bkcount (si existe)
if [ -d "backups/$1/$bkcount" ]
then
    rm backups/$1/$bkcount/*
else
    mkdir backups/$1/$bkcount
fi


# hago la copia
cp -p $2/catalis/bases/catalis/$1/* backups/$1/$bkcount/ >> backups/$1/logs.txt 2>> backups/$1/logerror.txt
