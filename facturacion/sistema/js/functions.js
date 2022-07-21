$(document).ready(function(){

    //--------------------- SELECCIONAR FOTO PRODUCTO ---------------------
    $("#foto").on("change",function(){
    	var uploadFoto = document.getElementById("foto").value;
        var foto       = document.getElementById("foto").files;
        var nav = window.URL || window.webkitURL;
        var contactAlert = document.getElementById('form_alert');
        
            if(uploadFoto !='')
            {
                var type = foto[0].type;
                var name = foto[0].name;
                if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png')
                {
                    contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';                        
                    $("#img").remove();
                    $(".delPhoto").addClass('notBlock');
                    $('#foto').val('');
                    return false;
                }else{  
                        contactAlert.innerHTML='';
                        $("#img").remove();
                        $(".delPhoto").removeClass('notBlock');
                        var objeto_url = nav.createObjectURL(this.files[0]);
                        $(".prevPhoto").append("<img id='img' src="+objeto_url+">");
                        $(".upimg label").remove();
                        
                    }
              }else{
              	alert("No selecciono foto");
                $("#img").remove();
              }              
    });

    $('.delPhoto').click(function(){
        // Agrega caracteristicas por medio de la class y un id 
        // funcion para remover la foto
    	$('#foto').val('');
    	$(".delPhoto").addClass('notBlock');
    	$("#img").remove();

        // Si existe una foto y la quieren remover, entonces se toma el valor de  
        // img_producto.png que es la img por defecto para guardar ese dato
        if($("$foto_actual") && $("#foto_remove")){
            $("#foto_remove").val('img_producto.png');
        }

    });

    //------ Modal Form agregar porducto
    $('.add_product').click(function(e){
        e.preventDefault();
        // Accedemos al atributo product para obtener el id de producto
        var producto = $(this).attr('product');   
        var action = 'infoProducto';
        var Precio = 'Precio :';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true, 
            // Datos enviar al archivo ajax.php
            data:{action:action, producto:producto},

            success:function(response){ 
                console.log(response);
                if(response != 'error'){
                    // Convertimos el objeto info en un formato JSON   
                     
                    var info = JSON.parse(response); 

                    // Obtencion de los datos para su visualizacion
                    $('#producto_id').val(info.codproducto);
                    $('.nameProducto').html(info.descripcion);
                    $('.unidades_producto').html(info.existencia);
                    $('.precio_producto').html(info.precio);

                    $('.bodyModal').html('<form action="" method="post" name="form_add_product" id="form_add_product" onsubmit="event.preventDefault(); sendDataProduct(); "> ' +
                                                    ' <h1 class="iconos_cajas"> <i class="fa-solid fa-boxes-stacked" style="font-size: 45pt;"></i> <i class="fa-solid fa-shop" style="font-size: 45pt;"></i> <i class="fa-solid fa-boxes-stacked" style="font-size: 45pt;"></i>  </h1> <br>'+ 
                                                    
                                                    '<h1 class="ag_pr">    Agregar Productos </h1> ' + 
                                                    '<h2 class="nameProducto"> '+info.descripcion+'  </h2>'+  
                                    
                                                    //  Informacion en la ventana  
                                                               '<label for="precio_producto">  Precio: '+info.precio+'  </label> '+ 
                                                               '<p class="precio_producto">  Precio actualizado:  </p> '+
                                                                '<label for="unidades_producto">  Existencia: '+info.existencia+' </label> '+     
                                                               '<p class="unidades_producto">  Existencia actualizada:  </p> <br>'+
                                                    '<input type="number" name="precio" id="txtprecio" placeholder="Precio del Producto" require> <br>'+
                                                    '<input type="text" name="cantidad" id="txtcantidad" placeholder="Cantidad del Producto" require> <br>'+
                                                    
                                                    '<input type="hidden" name="producto_id" id="producto_id" value="'+info.codproducto+'" require>'+  
                                                    '<input type="hidden" name="action" value="addProduct"  require> '+ 
                                    
                                                    '<div class="alert alertAddProduct">   </div>'+
                                                    '<button type="submit" class="btn_new">   <i class="fas fa-plus"></i> Agregar </button>'+
                                                    '<a href="#" class="btn_ok_cerrar closeModal" onclick="closeModal();">   <i class="fa-solid fa-ban"></i> Cerrar </a>'+
                                                '</form>');
                }
            },

            error:function(error){
                console.log(error);
            }

        });
 
        // Abre la ventana emergente llamado a la clase 'modal' en la pensaña de header
        $('.modal').fadeIn();
    });

});


function sendDataProduct(){
    
    // Borrar datos cuando se da click en agregar product
    $('alertAddProduct').html('');

    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true, 
        // Datos enviar al archivo ajax.php
        data: $('#form_add_product').serialize(),

        success:function(response){  
            
                if(response == 'error'){ 
                    $('.alertAddProduct').html('<p style = "color: red; >Error al agregar el prodcuto. <p>');
                }else{
                    // Convertimos el objeto info en un formato JSON    
                    var info = JSON.parse(response); 

                    // Alterar las celdas de la tabla mediante su clase
                    $('.row' + info.producto_id+' .celPrecio').html(info.nuevo_precio);
                    $('.row' + info.producto_id+' .celExistencia').html(info.nueva_existencia);
                    $('.unidades_producto').html('Existencia actualizada: '+ info.nueva_existencia);
                    $('.precio_producto').html('Precio actualizado: '+ info.nuevo_precio);

                    // Limpiar campos
                    $('#txtcantidad').val('');
                    $('#txtprecio').val('');

                    $('.alertAddProduct').html('<p> Producto agreagado correctamente <p>');
                }

        }, 

        error:function(error){
            console.log(error);
        }

    });
    
}

// Cierra la pestaña 'modal'
function closeModal(){
    // Limpiar los campos
    $('.alertAddProduct').html('');
    $('#txtcantidad').val('');
    $('#txtprecio').val('');

    $('.modal').fadeOut();
}