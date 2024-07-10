// ! IMPORTANTE : EL MODAL REQUIERE JQUERY; POR LO QUE DEBE SER INCLUIDA LUEGO DE JQUERY


//Esconde el modal con una animacion de fade
function modalFadeOut(){
    $('#modalContainer').fadeOut(1000, function() {
        $(this).hide();
    });
}


function modalSetTitle(title){
    $("#modalTitle").html("<label style='font-weight:bold; color:white'>"+title+"</label>");
}

//muestra el modal con una animacion de fade
function modalFadeIn() {
    $('#modalContainer').css("display", "flex").css("opacity", 0).animate({ opacity: 1 }, 1000);
    $('#modalContent').scrollTop(0);

  }

//carga el html en mood string que se le pase, [sobre-escribe todo] !
function modalLoadContent(content){
    $("#modalContent").html(content);
}


//carga html y lo agrega al final del contenido del modal
function modalAddContent(content){
    $("#modalContent").append(content);
}

//borra la informacion del modal
function modalClear(){
    modalLoadContent("");
    modalSetTitle("");
}
