/**
 * Определяет ширину scrollBar'а документа
 * @returns {number}
 */
function getScrollbarWidth() {
    var outer = document.createElement("div");
    outer.style.visibility = "hidden";
    outer.style.width = "100px";
    outer.style.msOverflowStyle = "scrollbar"; // needed for WinJS apps

    document.body.appendChild(outer);

    var widthNoScroll = outer.offsetWidth;
    // force scrollbars
    outer.style.overflow = "scroll";

    // add innerdiv
    var inner = document.createElement("div");
    inner.style.width = "100%";
    outer.appendChild(inner);

    var widthWithScroll = inner.offsetWidth;

    // remove divs
    outer.parentNode.removeChild(outer);

    return widthNoScroll - widthWithScroll;
}

/**
 * Функция генерирует случайное число
 * @param min
 * @param max
 * @returns {*}
 */
function getRandomInt(min, max) {return Math.floor(Math.random() * (max - min + 1)) + min;}

/**
 * Функция клонирует объект
 * @param a
 * @returns {*}
 */
function cloneObject(a){if(null==a||"object"!=typeof a)return a;var c=a.constructor(),b;for(b in a)c[b]=cloneObject(a[b]);return c}

/**
 * Функция считает кол-во элементов объекта
 * @param ob
 * @returns {number}
 */
function countObject(ob) {var count=0;for (var i in ob) {count++;}return count}

/**
 * Аналог php - empty()
 * Возвращает истину, если переданное значение - пусто
 * @param a
 * @returns {boolean}
 */
function empty(a){return""==a||null==a||0==a||"0"==a||!1==a?!0:!1}

/**
 * Аналог php str_replace()
 * Заменяет подстроку в строке на переданное значение
 * Возвращает новую строку
 * @param b
 * @param a
 * @param c
 * @returns {*}
 */
function str_replace(b,a,c){if(!(a instanceof Array)&&(a=Array(a),b instanceof Array))for(;b.length>a.length;)a[a.length]=a[0];for(b instanceof Array||(b=Array(b));b.length>a.length;)a[a.length]="";if(c instanceof Array){for(d in c)c[d]=str_replace(b,a,c[d]);return c}for(var d=0;d<b.length;d++)for(var e=c.indexOf(b[d]);-1<e;)c=c.replace(b[d],a[d]),e=c.indexOf(b[d],e);return c}

/**
 * $('input').focus().selectRange(0,5);
 * Поставить фокус в поле и выделить первые 5 символов
 * @param start
 * @param end
 * @returns {*}
 */
$.fn.selectRange = function(start, end) {
    return this.each(function() {
        if (this.setSelectionRange) {
            this.focus();
            this.setSelectionRange(start, end);
        } else if (this.createTextRange) {
            var range = this.createTextRange();
            range.collapse(true);
            range.moveEnd('character', end);
            range.moveStart('character', start);
            range.select();
        }
    });
};

/**
 * Функция возвращает значение GET-переменной
 * @param name
 * @returns {string}
 */
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

/**
 * DETECT IE OLD VERSION
 */
var oldIE = false;
(function ($) {
    "use strict";
    if ($('html').is('.ie6, .ie7, .ie8')) {
        oldIE = true;
    }
}(jQuery));


/**
 * Функция возвращает максимальное значение переданного массива
 * @param array
 * @returns {number}
 */
function maxArrayValue(array) {
    return Math.max.apply(Math, array);
}


/**
 * Аналог функции на php in_array()
 * @param needle
 * @param haystack
 * @param strict
 * @returns {boolean}
 */
function in_array(needle, haystack, strict) {
    var found = false, key, strict = !!strict;
    for (key in haystack) {
        if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
            found = true;
            break;
        }
    }
    return found;
}


/**
 * Удаление класса средствами javascript
 * @param className
 */
function removeClass(className) {
    // convert the result to an Array object
    var els = Array.prototype.slice.call(
        document.getElementsByClassName(className)
    );
    for (var i = 0, l = els.length; i < l; i++) {
        var el = els[i];
        el.className = el.className.replace(
            new RegExp('(^|\\s+)' + className + '(\\s+|$)', 'g'),
            '$1'
        );
    }
}


/**
 * Ajax-загрузка файла с отслеживанием прогресса
 *
 * Пример входного параметра:
 *      $("input[type=file]")[0].files[0]
 *      document.forms.my_form.INPUT_FILE_NEME[0].files[0]
 *
 * @param file
 */
function upload(file) {
    var xhr = new XMLHttpRequest();

    // обработчик для закачки
    xhr.upload.onprogress = function(event) {
        console.log(event.loaded + ' / ' + event.total);
    }

    // обработчики успеха и ошибки
    // если status == 200, то это успех, иначе ошибка
    xhr.onload = xhr.onerror = function() {
        if (this.status == 200) {
            console.log("success");
        } else {
            console.log("error " + this.status);
        }
    };

    /*
     запрос будет отправлен на php-файл /upload_file.php
     где данные по загружаемому файлу будут доступны в суперглобальном массиве $_FILES["var_name"]
     */
    var formData = new FormData();
    formData.append("var_name", file);

    xhr.open("POST", "/upload_file.php");
    xhr.send(formData);
}