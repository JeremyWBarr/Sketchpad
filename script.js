function getCookie(name) {
    var re = new RegExp(name + "=([^;]+)");
    var value = re.exec(document.cookie);
    return (value != null) ? unescape(value[1]) : null;
}

function login() {
    var re = new RegExp("userLoggedIn=([^;]+)");
    var value = re.exec(document.cookie);
    if(value != null) {
        document.location.href = 'main/main.html';
    }
}

const API = 'sketchpad.php';

jQuery(document).ready(function(){
    jQuery('form').on('submit', function(e){
        var jqElm = jQuery(this);
        jqElm.parents('.section').find('div').html('');

        console.log(removeEmptyParams(jqElm.serialize()));
        var post = jQuery.post(API, removeEmptyParams(jqElm.serialize()), function(d){
            console.log(typeof d);
            if(typeof d === "object"){
                jqElm.parents('.section').find('div').html(
                    JSON.stringify(d, undefined, 2)).addClass('pre');
            } else {
                try{
                    jqElm.parents('.section').find('div').html(
                        JSON.stringify(JSON.parse(d), undefined, 2)).addClass('pre');
                } catch(e) {
                    jqElm.parents('.section').find('div').html(d).removeClass('pre');
                }
            }
        });

        post.fail(function(elm, status, error){
            jqElm.parents('.section').find('div').html(error).removeClass('pre');
        });

        e.preventDefault();
    });
});

function removeEmptyParams(string){
    return string.replace(/&{0,1}\w+=(&|$)/g, "$1");
}