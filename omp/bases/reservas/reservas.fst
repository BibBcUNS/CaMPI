1 0 v1^b,'_',v1^c,'_',v1^p
2 0 (v2^i/)
3 0 (v3^i/)
2 5 '|RES=|',(v2^i/)
3 5 '|ESP=|',(v3^i/)
3 5 '|ESP_CONF=|',(if v3^e='CONFIRMADA' then v3^i/ fi)
3 5 '|ESP_PEND=|',(if v3^e<>'CONFIRMADA' then v3^i/ fi)
4 0 '-NC=',v1^c
