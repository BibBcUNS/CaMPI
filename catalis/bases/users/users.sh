# ----------------------------------------------------
# Catalis - users.sh
#
# Creacion de la base de datos de usuarios + permisos
# ----------------------------------------------------

# Necesitamos asegurar una línea en blanco final
echo>>users.txt

# Creamos la base de usuarios
id2i users.txt create=users

# Generamos el diccionario
mx users "fst=@users.fst" fullinv=users

echo
echo La base users ha sido generada
echo

# Detectamos usuarios repetidos
mx dict=users "pft=if val(v1^t) <> 1 then /#,'  *** ATENCION: ',v1^*, ' tiene mas de un posting!' /, fi" now -all

# Detectamos bases sin permiso de acceso definido
mx users "pft=(if not '~1~2~3~' : s('~'v5^p'~') then /#,'  *** ATENCION: usuario ',v1[1], ', base ',v5^*,': falta definir permiso de acceso!' /, fi)" now -all
