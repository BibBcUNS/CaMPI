1 0 mhl,"Nombre="v1/
2 0 mhl,"DNI="v2/
3 0 mhl,"Fecha="v3^f*6.4,'/',v3^f*3.2,'/',v3^f.2/,if val(v3^h)>230000 then 'Turno=Tarde' else 'Turno=Dia' fi/
4 0 mhl,(|Operacion=|v4^t,if v4^m:'s' then ' Morosa' fi,if s(mhu,v4^t)='PRESTAMO' then mpl,if v4^o:'sala' then ' Sala' else ' Domicilio' fi fi/,mpl,|Tematica=|v4^c/,|Inventario=|v4^i/,|Vencimiento=|v4^v/,|Material=|v4^l,if v4^t:'sancion' then "Dias sancion="v4^d/,"Nro sancion="v4^o/ fi,'%')
5 0 mhl,"Operario="v5/
6 0 mhl,"Terminal="v6/