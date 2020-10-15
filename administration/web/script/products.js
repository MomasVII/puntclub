
// Check if Field is in price format XXXXXX.XX
function isPriceFormat(formField) {
    var myValue = $(formField).val();
    var myValueNum = Number(myValue);
    if(isNaN(myValueNum)){
        myValueNum = 0;
    }
    $(formField).val(myValueNum.toFixed(2));

    var switchID = $(formField).attr("ID");
    switchID = "#enabled"+switchID.substr(4);
    if(myValueNum>0){
        $(switchID).value="1";
        $(switchID).prop('checked',true);
    } else {
        $(switchID).value="0";
        $(switchID).prop('checked',false);
    }
}

