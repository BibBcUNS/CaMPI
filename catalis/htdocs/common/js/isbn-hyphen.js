
// =============================================================================
//  Catalis - isbn-hyphen.js
// =============================================================================


//  Based on:
//
//  ISBN-ISSN.php  - ISBN/ISSN processing functions in PHP4.
//  Version 0.97
//  Robert D. Cameron, April 16, 2001.
//  Copyright (c) 2001,  GNU Public License, Version 2, applies.
//
//  http://usin.org/software/servers/ISBN-ISSN.php (19/IX/2003)

var country_group_partition = ['0', '80', '950', '9960', '99900'];

var country_group_map = {
    '0' : [['00',200,7000,85000,900000,9500000], 
                     "English group 0:AU:CA:GI:IE:NZ:PR:ZA:SZ:GB:US:ZW"],
    '1' : [['00000000',55000,869800,9999900], 
                     "English group 1:AU:CA:GI:IE:NZ:PR:ZA:SZ:GB:US:ZW"],
    '2' : [['00',200,40000000,500,7000,84000,900000,9500000], 
                     "French group:FR:BE:CA:LU:CH"],
    '3' : [['00',200,7000,85000,900000,9500000], 
                     "German group:AT:DE:CH"],
    '4' : [['00',200,7000,85000,900000,9500000], "JP"],
    '5' : [['00',200,7000,85000,900000,9500000], 
         "Former USSR group:RU:AM:AZ:BY:EE:GE:KZ:KG:LV:LT:MD:TJ:TM:UA:UZ"],
    '7' : [['00',100,5000,80000,900000], "CN"],
    '80' : [['00',200,7000,85000,900000], "Czech/Slovak:CZ:SK"],
    '81' : [['00',200,7000,85000,900000], "IN"],
    '82' : [['00',200,7000,90000,990000], "NO"],
    '83' : [['00',200,7000,85000,900000], "PL"],
    '84' : [['00',200,7000,85000,900000,95000,9700], "ES"],
    '85' : [['00',200,7000,85000,900000], "BR"],
    '86' : [['00',300,7000,80000,900000], "Balkans:YU:BA:HR:MK:SI"],
    '87' : [['00',400,7000,85000,970000], "DK"],
    '88' : [['00',200,7000,85000,900000], "Italian group:IT:CH"],
    '89' : [['00',300,7000,85000,950000], "Korean group:KP:KR"],
    '90' : [['00',200,5000,70000,800000,9000000], "Dutch group:NL:BE"],
    '91' : [['0',20,500,6500000,7000,8000000,85000,9500000,970000], "SE"],
    '92' : [['0',60,800,9000], "INT"], // International organizations
    '93' : [['0000000'], "IN"],
    '950' : [['00',500,9000,99000], "AR"],
    '951' : [['0',20,550,8900,95000], "FI"],
    '952' : [['00',200,5000,89,9500,99000], "FI"],
    '953' : [['0',10,150,6000,96000], "HR"],
    '954' : [['00',400,8000,90000], "BG"],
    '955' : [['0',20,550,800000,9000,95000], "LK"],
    '956' : [['00',200,7000], "CL"],
    '957' : [['00',440,8500,97000], "TW"],
    '958' : [['0',600,9000,95000], "CO"],
    '959' : [['00',200,7000], "CU"],
    '960' : [['00',200,7000,85000], "GR"],
    '961' : [['00',200,6000,90000], "SI"],
    '962' : [['00',200,7000,85000], "HK"],
    '963' : [['00',200,7000,85000], "HU"],
    '964' : [['00',300,5500,90000], "IR"],
    '965' : [['00',200,7000,90000], "IL"],
    '966' : [['00',500,7000,90000], "UA"],
    '967' : [['0',60,900,9900,99900], "MY"],
    '968' : [['000000',10,400,500000,6000,800,900000], "MX"],
    '969' : [['0',20,400,8000], "PK"],
    '970' : [['00',600,9000,91000], "MX"],
    '971' : [['00',500,8500,91000], "PH"],
    '972' : [['0',20,550,8000,95000], "PT"],
    '973' : [['0',20,550,9000,95000], "RO"],
    '974' : [['00',200,7000,85000,900000], "TH"],
    '975' : [['00',300,6000,92000,980000], "TR"],
    '976' : [['0',40,600,8000,95000], 
         "Caribbean Community:AG:BS:BB:BZ:KY:DM:GD:GY:JM:MS:KN:LC:VC:TT:VG"],
    '977' : [['00',200,5000,70000], "EG"],
    '978' : [['000',2000,30000], "NG"],
    '979' : [['0',20,300000,400,700000,8000,95000], "ID"],
    '980' : [['00',200,6000], "VE"],
    '981' : [['00',200,3000], "SG"],
    '982' : [['00',100,500000], 
                 "South Pacific:CK:FJ:KI:MH:NR:NU:SB:TK:TO:TV:VU:WS"],
    '983' : [['000',2000,300000,50,800,9000,99000], "MY"],
    '984' : [['00',400,8000,90000], "BD"],
    '985' : [['00',400,6000,90000], "BY"],
    '986' : [['000000'], "TW"],
    '987' : [['00',500,9000,99000], "AR"],
    '9952' : [['00000'], "AZ"],
    '9953' : [['0',20,9000], "LB"],
    '9954' : [['00',8000], "MA"],
    '9955' : [['00',400], "LT"],
    '9956' : [['00000'], "CM"],
    '9957' : [['00',8000], "JO"],
    '9958' : [['0',10,500,7000,9000], "BA"],
    '9959' : [['00'], "Libya"],
    '9960' : [['00',600,9000], "SA"],
    '9961' : [['0',50,800,9500], "DZ"],
    '9962' : [['00000'], "PA"],
    '9963' : [['0',30,550,7500], "CY"],
    '9964' : [['0',70,950], "GH"],
    '9965' : [['00',400,9000], "KZ"],
    '9966' : [['00',70000,800,9600], "KE"],
    '9967' : [['00000'], "KG"],
    '9968' : [['0',10,700,9700], "CR"],
    '9970' : [['00',400,9000], "UG"],
    '9971' : [['0',60,900,9900], "SG"],
    '9972' : [['0',40,600,9000], "PE"],
    '9973' : [['0',10,700,9700], "TN"],
    '9974' : [['0',30,550,7500], "UY"],
    '9975' : [['0',50,900,9500], "MD"],
    '9976' : [['0',60,900,99000,9990], "TZ"],
    '9977' : [['00',900,9900], "CR"],
    '9978' : [['00',950,9900], "EC"],
    '9979' : [['0',50,800,9000], "IS"],
    '9980' : [['0',40,900,9900], "PG"],
    '9981' : [['0',20,800,9500], "MA"],
    '9982' : [['00',40000,800,9900], "ZM"],
    '9983' : [['00',500,80,950,9900], "GM"],
    '9984' : [['00',500,9000], "LV"],
    '9985' : [['0',50,800,9000], "EE"],
    '9986' : [['00',400,9000], "LT"],
    '9987' : [['00',400,8800], "TZ"],
    '9988' : [['0',30,550,7500], "GH"],
    '9989' : [['0',30,600,9700], "MK"],
    '99901' : [['00'], "BH"],   
    '99903' : [['0',20,900], "MU"],
    '99904' : [['0',60,900], "AN"],
    '99905' : [['0',60,900], "BO"],
    '99906' : [['0',60,900], "KW"],
    '99908' : [['0',10,900], "MW"],
    '99909' : [['0',40,950], "MT"],
    '99910' : [['0000'], "SL"],
    '99911' : [['00',600], "LS"],
    '99912' : [['0',60,900], "BW"],
    '99913' : [['0',30,600], "AD"],
    '99914' : [['0',50,900], "SR"],
    '99915' : [['0',50,800], "FK"],
    '99916' : [['0',30,700], "NA"],
    '99917' : [['0',30], "BN"],
    '99918' : [['0',40,900], "FO"],
    '99919' : [['0',40,900], "BJ"],
    '99920' : [['0',50,900], "AD"],
    '99921' : [['0',20,700], "QA"],
    '99922' : [['0',50], "GT"],
    '99923' : [['0',20,800], "SV"],
    '99924' : [['0',30], "NI"],
    '99925' : [['0',40,800], "PY"],
    '99926' : [['0000',600], "HN"],
    '99927' : [['0',30,600], "AL"],
    '99928' : [['0',50,800], "GE"],
    '99929' : [['0000'], "MN"],
    '99930' : [['0',50,800], "AM"],
    '99931' : [['0000'], "SC"],
    '99932' : [['0',10], "MT"],
    '99933' : [['00',300], "NP"],
    '99934' : [['0'], "DO"],
    '99935' : [['0000'], "HT"],
    '99936' : [['0000'], "BT"],
    '99937' : [['0',20], "MO"]
}

//
// ISN_clean: remove hyphens and transform check digit 'x' to 'X'. )
//
function ISN_clean (isbn_proto) {
	return isbn_proto.replace(/-/g,"").replace(/x/g,"X");
}


function prefix_length_from_map (s, map) {
  for (var i=1; i < map.length; i++) {
		//document.write(s + "---" + map[i-1] + "<br>");
		if (s.toString() < map[i].toString()) {
		  //document.write("=>" + map[i-1] + "---" + map[i-1].toString().length + "<br>");
	    return map[i-1].toString().length;
		}
  }
  return map[i-1].length;
}


function country_group_code (isbn_proto) {
  isbn_proto = ISN_clean(isbn_proto);
  cglen = prefix_length_from_map(isbn_proto, country_group_partition);
  return isbn_proto.substr(0,cglen);
}

//
// Generate the canonical hyphenated form of an ISBN.
// (Checksum-valid ISBN assumed as input).
//
function canonical_ISBN (isbn_proto) {
  isbn_proto = ISN_clean(isbn_proto);
  cg = country_group_code(isbn_proto);
  cglen = cg.length;
  pubandbook = isbn_proto.substr(cglen, 9-cglen);
  checkdigit = isbn_proto.substr(9,1);
  //if (is_array($country_group_map[$cg])) {
	if (country_group_map[cg]) {
	  //document.write("X1" + "<br>");
    publen = prefix_length_from_map(pubandbook, country_group_map[cg][0]);
		//document.write("publen=" + publen + "<br>");
    if (cglen + publen == 9) { 
		  //document.write("X2" + "<br>");
		  return cg + "-" + pubandbook + "-" + checkdigit;
		}
    else {
		  //document.write("X3" + "<br>");
      pubcode = pubandbook.substr(0, publen);
      bookno = pubandbook.substr(publen);
      return cg + "-" + pubcode + "-" + bookno + "-" + checkdigit;
    }
  }
  else {
	  //document.write("X4" + "<br>");
	  return cg + "-" + pubandbook + "-" + checkdigit;
	}
}

/*
document.write(canonical_ISBN("950-511-858-9") + "<hr>");
document.write(canonical_ISBN("950-43-2883-0") + "<hr>");
document.write(canonical_ISBN("8476352166") + "<hr>");
*/
