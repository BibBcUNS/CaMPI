if p(v859) then  /* Si no existe el campo 859, no hacemos nada */
    'd859',      /* Esto borra todas las ocurrencias del 859 */

    /* Ahora, ocurrencia por ocurrencia, vemos si hay que modificarla o dejarla como está */
    (,
        'a859~',
            if v859^b = 'BAJA' then  /* El test lo realizo sobre esta ocurrencia en particular, no sobre el campo 859 en general */
                    replace(v859, s('^b',v859^b), '^bbaja'),  /* Asigno un nuevo valor al subcampo g, si ya existía este subcampo */
            else
                v859,  /* Dejo la ocurrencia intacta */
            fi
        '~',
    ),
fi,