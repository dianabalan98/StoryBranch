
function checkRadio(element) {
  var id = element.id
  uncheckAll(id)
  img = document.getElementById(id+"Img")
  img.setAttribute('src', '/svg/emotions/' + id + '_colored.svg')
  element.checked = true
}

function uncheckAll(checkedId){
  $(".emotion").each(function() {
      id = $(this).val()
      if(id != checkedId) {
        $("#"+id).prop('checked', false);
        img = document.getElementById(id+"Img")
        img.setAttribute('src', '/svg/emotions/' + id + '_gray.svg')
      }
  });

}
