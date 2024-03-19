'd859',('a859~',
v859^*,'^aAr-BaUNS',
if (v859^d:'PROCEDENCIA DCM' or v859^d:'PROCEDENCIA CM' or v859^d:'PROCEDENCIA DCS' or v859^s:'PROCEDENCIA DCM' or v859^s:'PROCEDENCIA CM' or v859^s:'PROCEDENCIA DCS') and v859^b:'referencias' 
	then
		'^bCSCS',
else
	if (v859^h:'040' and v859^i:'B148' and v859^b:'SA') 
		then
			'^bSAT',
	else
		if (v859^k:'R' and v859^h:'040' and v859^i:'B148' and (v859^b:'Referencias' or v859^b:'referencias')) 
			then
				'^bST',
		else		
			if (v859^v:'L.F' and v859^b:'SA') 
				then 
					'^bLF',
			else
				if (v859^b:'MM' or v859^b:'referencias') and (v859^3:'cd' or v859^3:'dvd' or v859^v:'cd' or v859^v:'dvd') 
					then 
						'^bT',
				else
					'^b',v859^b
				fi,
				
			fi
		fi,
	fi,	
fi, 

if p(v859^c) then '^c',v859^c fi,
if p(v859^d) then '^d',v859^d fi,
if p(v859^e) then '^e',v859^e fi,
if p(v859^f) then '^f',v859^f fi,
if p(v859^h) then '^h',v859^h fi,
if p(v859^i) then '^i',v859^i fi,
if p(v859^j) then '^j',v859^j fi,
if p(v859^k) then '^k',v859^k fi,
if p(v859^l) then '^l',v859^l fi,
if p(v859^n) then '^n',v859^n fi,
if p(v859^o) then '^o',v859^o fi,
if p(v859^p) then '^p',v859^p fi,
if p(v859^q) then '^q',v859^q fi,
if p(v859^r) then '^r',v859^r fi,
if p(v859^s) then '^s',v859^s fi,
if p(v859^t) then '^t',v859^t fi,
if p(v859^u) then '^u',v859^u fi,
if p(v859^v) then '^v',v859^v fi,
if p(v859^w) then '^w',v859^w fi,
if p(v859^x) then '^x',v859^x fi,
if p(v859^y) then '^y',v859^y fi,
if p(v859^z) then '^z',v859^z fi,
if p(v859^3) then '^3',v859^3 fi,
'~') 
