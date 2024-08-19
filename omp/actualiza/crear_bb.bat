#!/bin/bash

/opt/cisis/mx dcic "proc='d1a1~dcic',v1,'~'" create=biblio  now -all

cp -f origen/bibliografica/biblio.mst destino/marc/marc.mst
cp -f origen/bibliografica/biblio.xrf destino/marc/marc.xrf

/opt/cisis/mx marc "fst=@marc.fst" fullinv=destino/marc/marc uctab=files/ucans.tab actab=files/acans.tab
/opt/cisis/mx marc "fst=@marc_exist.fst" fullinv=destino/marc/exist/exist
