/**
语言包替换
key string 需要翻译的文本（语言包中的键值）
params object 需要替换的参数（动态变量的键对值）
**/
function LangT(key,params) {

	//console.log(langjson);
	langjson=typeof(langjson)=='object'?langjson:JSON.parse(langjson);
	var rs = langjson && langjson[key] ? langjson[key] : key;

	for (var k in params){
		var r = new RegExp('{'+k+'}', "ig");
		var re=params[k];
		rs=rs.replace(r, re);
	}
	return  rs;
}


