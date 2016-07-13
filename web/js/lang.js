/*
* lang.js v 1.0.0
* date : 2016 05 02
* author : jane&sueSky
*/


;!function(global){

	var arrayPrototype = Array.prototype;
    var objectPrototype = Object.prototype;
	var defineProperty = Object.defineProperty;
    var W3C = document.dispatchEvent;

    /*
    * 缓存一些常用的原型方法
    */

    var core_slice = arrayPrototype.slice;
    var core_push = arrayPrototype.push;
    var toString = objectPrototype.toString;
    var hasOwn = objectPrototype.hasOwnProperty;


    /*
	* 常用正则
    */

    var rword = /[^, ]+/g;

    /*
	* 链式核心架构
	* 链式原作者库 underscore.js
	* LANG([1,2,3,4]).each(function(){ return [1,2,3] }).map(function(i){ return i }).value();
	* 不使用链式时
	* LANG.each([1,2,3,4], function(i){ console.log(i) })
	* 使用链式调用时，所有返回值都会自动转化为第一个参数
    */


	var version = '1.0.0';

	var LANG = function (obj){

		// 如果自身已实例返回自身, 防止重复调用
		if(obj instanceof LANG){
			return obj
		}

		// 不存在则实例
		if(!(this instanceof LANG)){
			return new LANG(obj);
		}

		// 实例之后的返回值缓存
		this._objCache = obj;
	}

	LANG.fn = LANG.prototype;

	/*
	* 用于浅拷贝, 于性能起见, 不混入深拷贝, 最后一个参数为boolean则表示是否覆盖原属性, 默认为覆盖 true
	* 将多个对象整合进第一个参数
    */

    //问题1. extend的深拷贝 要为lang.string.xxx添加到链式架构中 lang.xxx
    //问题2. 为其prototype拓展方法时 要一起拓展至function对象
    //问题3. 要为其原型添加 原生方法使其可以链式调用
    LANG.extend = function(){

		var boolRide, key, inherit,
			args = core_slice.call(arguments),
			target = args[0],
			i = 1;

		if(typeof target === "boolean"){
			boolRide = true;
			args.shift()
			target = args[0];
		}

		if (typeof target !== "object" && !LANG.isFunction(target)) {
	        target = {}
	    }

		if(i === args.length){
			target = LANG;
			i--;
	    }

	    while(inherit = args[i++]){
	        for(key in inherit){
	        	if(hasOwn.call(inherit, key) && (!boolRide || !(key in target))){
	    			target[key] = inherit[key]
	            }
	        }
	    }

	    return target;
	}
    
    /*
	* 类型判断
	* 判断全转化为原生形式 typeof obj === 'object' 同理 LANG.type(obj) === 'object'
	* isArrayLike不稳定， 仅供内部使用
    */

    /*
	* is系列不依赖于任何模块
    */

    var rwindow = /^\[object (?:Window|DOMWindow|global)\]$/;
    var rfunction = /^\s*\bfunction\b/;
    var rnative = /\[native code\]/ //判定是否原生函数
	var rarraylike = /(Array|List|Collection|Map|Arguments)\]$/;
    var class2Type = {};


    'Boolean, Number, String, Function, Array, Date, RegExp, Object, Error, Arguments'.replace(rword, function(name){
		var _name;
		class2Type[ _name = '[object '+ name +']' ] = name.toLowerCase();
		LANG[ 'is' + name ] = function(obj){
			return toString.call(obj) === _name;
		}
    })

    //此方法不稳定 不提供接口


    LANG.type = function(obj){
    	
    	if(obj == null){
    		return String(obj);
    	}

    	return typeof obj === 'object' || typeof obj === 'function' ?
            class2Type[ toString.call(obj) ] || 'object' : typeof obj;
    }

    LANG.isObject = function (a) {

        return a !== null && typeof a === 'object'

    }

    LANG.isFunction = typeof alert === 'object' ? function(fn){
    	//IE bug alert BOM.location.href DOM.get is a object
    	try {
	        return rfunction.test(fn + '')
	    } catch (e) {
	        return false
	    }

    } : function(fn){

    	return toString.call(fn) === '[object Function]' ;

    }

    LANG.isArray = Array.isArray || function(a){

    	return typeof toString.call(a) === '[object Array]';

    }

    LANG.isWindow = function (obj) {

	    if (!obj)
	        return false
	    // 利用IE678 window == document为true,document == window竟然为false的神奇特性
	    // 标准浏览器及IE9，IE10等使用 正则检测
	    return obj == obj.document && obj.document != obj //jshint ignore:line

	}

	function isWindow(obj) {

	    return rwindow.test(toString.call(obj))

	}

	if (isWindow(global)) {

	    LANG.isWindow = isWindow

	}

	//此方法并不稳定，慎重使用
	LANG.isPlainObject = function (obj, key) {
		//排除不是object
	    if (!obj || LANG.type(obj) !== 'object' || obj.nodeType || LANG.isWindow(obj)) {
	        return false
	    }

	    //剩余object ， 判定继承与非继承属性
	    //IE内置对象没有constructor
	    //IE8 9会抛错
	    try { 
	        if (obj.constructor &&
	                !hasOwn.call(obj, 'constructor') &&
	                !hasOwn.call(obj.constructor.prototype || {}, 'isPrototypeOf')) {
	            return false
	        }
	    } catch (e) { 
	        return false
	    }

	    for (key in obj) {
	    }

	    return key === void 0 || hasOwn.call(obj, key)
	}

	function isArrayLike(obj) {

		if (!obj)
			return false

		var n = obj.length

		if (n === (n >>> 0)) { //检测length属性是否为非负整数

			var type = toString.call(obj).slice(8, -1)

			if (rarraylike.test(type))
				return false;

			if (type === 'Array')
				return true;

			try {
				if ({}.propertyIsEnumerable.call(obj, 'length') === false) { //如果是原生对象
					return rfunction.test(obj.item || obj.callee)
				}
				return true
			} catch (e) { //IE的NodeList直接抛错
				return !obj.window; //IE6-8 window
			}

		}
		return false;
	}

	//可测 null undefined object array string 类数组
	LANG.isEmpty = function(obj) {

	    if (obj == null) return true;
	    
	    if (isArrayLike(obj) && (LANG.isArray(obj) || LANG.isString(obj) || LANG.isArguments(obj))) return obj.length === 0;
	    
	    return Object.keys(obj).length === 0;

	};

	LANG.isEmptyObject = function(obj){
		for (var i in obj){
			return false;
		}
		return true;
	};



	/*
	* 常用方法 语言底层缺陷修复 旧浏览器未实现，但已标准并被浏览器广泛拓展的方法 不依赖任何模块，后期此模块可直接删除
	*/

	//Aarry修复
	function iterator(vars, body, ret) {
	    var fun = 'for(var ' + vars + 'i=0, n=this.length; i<n; i++){' +
	            body.replace('_', '((i in this) && fn.call(scope,this[i],i,this))') +
	            '}' + ret;
	    return Function('fn, scope', fun)
	}

	//这样的判断不严谨， 随时都有可能有被覆盖的危险，最好用正则检索 native
	LANG.extend(false, Array.prototype, {
		//定位操作 javascrpit v1.8
		indexOf: function (item, index) {
			var n = this.length,
					i = ~~index;
			if (i < 0)
				i += n;
			for (; i < n; i++)
				if (this[i] === item)
					return i;
			return -1
		},
		lastIndexOf: function (item, index) {
			var n = this.length,
					i = index == null ? n - 1 : index;
			if (i < 0)
				i = Math.max(0, n + i);
			for (; i >= 0; i--)
				if (this[i] === item)
					return i;
			return -1
		},
        forEach: iterator('', '_', ''),
        filter: iterator('r=[],j=0,', 'if(_)r[j++]=this[i]', 'return r'),
        map: iterator('r=[],', 'r[i]=_', 'return r'),
        some: iterator('', 'if(_)return true', 'return false'),
        every: iterator('', 'if(!_)return false', 'return true')
	})

	//string修复
	LANG.extend(false, String.prototype, {
    	trim:function(){
    		//空格符 unicode换行符 16进制转义符
		    var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
		    return this.replace(rtrim, '')
    	}
    });

	//object修复
    var hasDontEnumBug = !({'toString': null}).propertyIsEnumerable('toString');
	var hasProtoEnumBug = (function(){}).propertyIsEnumerable('prototype');
	var dontEnums = [
	        'toString',
	        'toLocaleString',
	        'valueOf',
	        'hasOwnProperty',
	        'isPrototypeOf',
	        'propertyIsEnumerable',
	        'constructor'
	    ];
	var dontEnumsLength = dontEnums.length;

	LANG.extend(false, Object, {
    	keys: function (object) { //ecma262v5 15.2.3.14
	        var theKeys = [];
	        var skipProto = hasProtoEnumBug && typeof object === 'function'
	        if (typeof object === 'string' || (object && object.callee)) {
	            for (var i = 0; i < object.length; ++i) {
	                theKeys.push(String(i))
	            }
	        } else {
	            for (var name in object) {
	                if (!(skipProto && name === 'prototype') &&
	                        hasOwn.call(object, name)) {
	                    theKeys.push(String(name))
	                }
	            }
	        }

	        if (hasDontEnumBug) {
	            var ctor = object.constructor, //对象的构造函数
	                    skipConstructor = ctor && ctor.prototype === object //对象的原型 是否等于 object
	            for (var j = 0; j < dontEnumsLength; j++) {
	                var dontEnum = dontEnums[j]
	                if (!(skipConstructor && dontEnum === 'constructor') && hasOwn.call(object, dontEnum)) {
	                    theKeys.push(dontEnum)
	                }
	            }
	        }
	        return theKeys;
    	}
	});

	/*集合操作*/
	"filter, every, some".replace(rword, function(name){
		LANG[name] = function(obj, fn, scope){
			return obj[name](fn, scope);
		}
	});

	LANG.each = function(obj, fn, scope){
		var i = 0, value, ret=[];
		var map = arguments[3] === "map";
		var isArray = isArrayLike(obj);
		if(isArray){
			for(var len = obj.length; i<len; i++){
				value = fn.call(scope || obj[i], obj[i], i);
				if(map){
					if(value != null)
						ret[ ret.length ] = value;
				}
			}
		}else{
			for(i in obj){
				value = fn.call(scope || obj[i], obj[i], i);
				if(map){
					if(value != null)
						ret[ ret.length ] = value;
				}
			}
		}
		return map ? ret : obj;
	};

	LANG.map = function(obj, fn, scope){
		return this.each(obj, fn, scope, "map");
	};

	LANG.reduce = function(){};

	LANG.size = function(){};

	LANG.sample = function(){};

	LANG.pluck = function(){};

	LANG.object = function(){};

	LANG.find = function(){};

	/*
	 * 原型以及函数对象拓展
	 * 当为原型拓展时自动为其函数对象拓展
	 */

	LANG.fn.extend = function(target, obj){

		var boolRide, _super = LANG;

		if(!obj){
			obj = target;
			target = void 0;
		}

		if(typeof target === "string") {
			if((_super = LANG[target]) && LANG.isObject(_super)){
				_super = LANG[target];
			}else{
				_super = LANG[target] = {};
			}
		}

		//确保是一个复杂的数据结构
		if(typeof obj !== "object" && !LANG.isFunction(obj)){
			obj = {}
		}

		Object.keys(obj).join(', ').replace(rword, function(name){
			if(name === "extend" || name === "fn") return;
			var func = _super[name] = obj[name];
			LANG.fn[name] = function(){
				var args = [this._objCache];
				core_push.apply(args, arguments);
				return LANG(func.apply(this, args))
			}
		})
	};

	LANG.fn.extend(LANG);

	LANG.fn.end = function(){
		return this._objCache;
	};


	/* LANG.String模块 添加基础方法*/

	LANG.fn.extend('String', {
		//把-_替换为大写
		trim:function(str){
			return str.trim();
		},
		camelize: function(target){
			if(target.indexOf("-") < 0 && target.indexOf("_") < 0){//CSS渲染时的效率
				return target;
			}
			return target.replace(/[-_][^-_]/g, function(value){
				return value.charAt(1).toUpperCase();
			})
		},
		//把大写替换为_
		underscored: function(target){
			return target.replace(/([a-z\d])([A-Z]+)/g, "$1_$2").replace(/\-/g,"_").toLowerCase();
		},
		format: function(str, object) {
			var rformat = /\\?\#{([^{}]+)\}/gm;
			var array = core_slice.call(arguments, 1);
			return str.replace(rformat, function(match, name) {
				if (match.charAt(0) === "\\")
					return match.slice(1);
				var index = Number(name);
				if (index >= 0)
					return array[index];
				if (object && object[name] !== void 0)
					return object[name];
				return '';
			});
		},
		formatTpl: function ( id, data ){

			var startTag = '<%';
			var endTag = '%>';

			var regExp = function(str){
				return new RegExp(str, 'g');
			}

			var query = function(type){
				var types = [
					'=([\\s\\S])+?',
					'([^{=}])*?'
				][ type || 0 ];
				return regExp(startTag + types + endTag)
			}

			var escapeHTML = function (content) {
				var escapeMap = {
					"<": "&#60;",
					">": "&#62;",
					'"': "&#34;",
					"'": "&#39;",
					"&": "&#38;"
				};
				return content.replace(/&(?![\w#]+;)|[<>"']/g, function (s){
					return escapeMap[s];
				});
			};

			var error = function(error, tplog){
				typeof console === 'object' && console.error( error + '\n'+ (tplog || ''));
				return error;
			}

			/**
			 * @param   {String}  模板名或模板
			 * @param   {Object}  数据
			 */

			var template = function(id, data){
				if(typeof id !== 'string') {
					return error('Template not found');
				}
				return template.render( id, data )
			};

			var cacheStore = template.cache = {};

			/**
			 * 获取编译函数
			 * @param   {String}    模板名
			 * @param   {Function}  数据
			 */

			template.get = function(id){
				var cache, boolRide = false;
				if(cacheStore[id]){
					cache = cacheStore[id];
				}else if(typeof document === 'object'){
					var elem = document.getElementById(id);
					if (elem) {
						cache = template.parse(
								(elem.value || elem.innerHTML).trim())
						boolRide = true;
					}else{
						cache = template.parse(id.trim())
					}
				}
				return {
					cache: cache,
					hasCache: boolRide
				};
			}

			/**
			 * 设置缓存
			 * @param id
			 * @param obj
			 */

			template.set = function(id, obj){
				if(obj.hasCache){
					cacheStore[id] = obj.cache;
				}
			}

			/**
			 * 渲染模板
			 * @param id
			 * @param data
			 * @returns {*|{}}
			 */

			template.render = function(id, data){
				var c = template.get(id);
				template.set(id, c);
				try{
					return c.cache(data, escapeHTML)
				}catch(e){
					delete cacheStore[id];
					return error(e, id);
				}
			}

			/**
			 * 编译函数
			 * @param html
			 * @returns {Function}
			 */

			template.parse = function(html){

				html = html.replace(/[\r\t\n]/g, ' ')
						.replace(regExp(startTag+'='), startTag+'= ')
						.replace(regExp(endTag), ' '+endTag)
						.replace(/\\/g, '\\\\')
						.replace(/(?="|')/g, '\\')
						.replace(query(), function(str){
							str = str.replace(regExp('^'+startTag+'='+'|'+endTag+'$'), '');
							return '";' + str.replace(/\\/g, '') + '; view+="';
						}).replace(query(1), function(str){
							var start = '"+(';
							if(str.replace(/\s/g, '') === startTag+endTag){
								return '';
							}
							str = str.replace(regExp(startTag+'|'+endTag), '');

							if(/^#/.test(str)){
								str = str.replace(/^#/, '');
								start = '"+_escape_(';
							}
							return start + str.replace(/\\/g, '') + ')+"';
						});

				return new Function('data, _escape_', '"use strict";var view = "' + html + '";return view;');
			}
			return template(id, data);
		}
	})

	/*LANG.Array模块 添加基础方法*/

	LANG.fn.extend('Array', {
		first:function(array, n){
			if (array == null || array.length < 1) return void 0;
			if (n == null) return array[0];
		},
		last:function(array,n){
			if (array == null || array.length < 1) return void 0;
			if (n == null) return array[array.length - 1];
		},
		initial:function(array, n){
			return core_slice.call(array, 0, array.length - (n == null ? 1 : n));
		},
		rest:function(array, n){
			return core_slice.call(array, n == null ? 1 : n);
		},
		contains:function(target, item){//是否包含
			return !!~target.indexOf(item); // ~ 取反再减一
		},
		removeAt:function(target, n){//删除指定的索引
			return !!target.splice(n, 1).length
		},
		remove:function(target, item){//删除指定的值
			var n = target.indexOf(item);
			return ~n ? LANG.Array.removeAt(target, n) : false;
		},
		min:function(target){//取最小值
			return Math.min.apply(null, target);
		},
		max:function(target){//取最大值
			return Math.max.apply(null, target);
		},
		unique:function(){//去重
			/**/
		},
		compact:function(target){//过滤undefined, null
			return target.filter(function(el){
				return el != null;
			})
		},
		merge:function(first, second){//将参数二合并到参数一
			var i = ~~first.length;//强行将undefined, null, NaN转化为0
			for(var j=0; j<second.length; j++){
				first[i++] = second[j];
			}
			first.length = i;
			return first;
		}
	})

	/*LANG.Object模块 添加基础方法*/
	LANG.fn.extend('Object',{
		//列出可枚举的实例属性， 采用Object.keys方法
		keys:function(obj){
			return Object.keys(obj);
		},
		//列出可枚举的实例属性值，
		values:function(obj){
			var keys = Object.keys(obj);
			var length = keys.length;
			var values = Array(length);
			for (var i = 0; i < length; i++) {
				values[i] = obj[keys[i]];
			}
			return values;
		},
		//列出可枚举的实例属性与继承属性
		allKeys:function(obj){

		},
		//列出
		functions:function(obj){
			var names = [];
			for (var key in obj) {
				if (LANG.isFunction(obj[key])) names.push(key);
			}
			return names.sort();
		}
	})

	/*LANG.Function模块 添加基础方法*/
	LANG.fn.extend('Function', {
		bind:function(){},
		once:function(){},
		after:function(){},
		before:function(){}
	})

	"function" == typeof define ? define(function() {
		return LANG;
	}) : "undefined" != typeof exports ? module.exports = LANG : global.LANG = LANG;

}(window)


