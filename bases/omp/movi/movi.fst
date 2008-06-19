1 0 mhl,"Nombre="v1/
2 0 mhl,"DNI="v2/
3 0 mhl,"Fecha="v3^f*6.4,'/',v3^f*3.2,'/',v3^f.2/,if val(v3^h)>130000 then 'Turno=Vespertino' else 'Turno=Matutino' fi/
4 0 mhl,(|Operacion=|v4^t,if v4^m:'s' then '_Morosa' fi,if s(mhu,v4^t)='PRESTAMO' then mpl,if v4^o:'sala' then '_Sala' else '_Domicilio' fi fi/,mpl,|Tematica=|v4^c/,|Inventario=|v4^i/,|Vencimiento=|v4^v/,|Material=|v4^l,if v4^t:'sancion' then "Dias_sancion="v4^d/,"Nro_sancion="v4^o/ fi,'%')
5 0 mhl,"Operador="v5/
6 0 mhl,"Terminal="v6/
13 0 mhl,"Categoria="v13/
