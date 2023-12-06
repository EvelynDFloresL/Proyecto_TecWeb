// JSON BASE A MOSTRAR EN FORMULARIO
$(document).ready(function(){
    let edit = false;
   let entrar = true;
    //console.log('jQuery is working');
    $('#title-result').hide();
    fetchProductos();

    $('#search').keyup(function(e){

        if($('#search').val()) {
            let search = $('#search').val();
            
            $.ajax({
                url: 'backend/product-search.php',
                type: 'GET',
                data: {search},
                success: function (response) {
                    let contenidos = JSON.parse(response);
                    let template = '';
                    let templateP = '';
                    let templateS = '';
                    contenidos.forEach(contenido => {
                        template += `<li>
                        ${contenido.titulo}
                        </li>`
                    });
                    $('#container').html(template);
                    $('#title-result').show();
                    contenidos.forEach(contenido => {
                        if (contenido.tipo === 'pelicula') {
                            templateP += `
                            <tr titleId="${contenido.id_contenido}">
                                        <td>${contenido.id_contenido}</td>
                                        <td>${contenido.region}</td>
                                        <td>${contenido.genero}</td>
                                        <td><a href="#" class="contenidoItem">${contenido.titulo}</a></td>
                                        <td>${contenido.duracion}</td>
                                        <td>
                                            <button class="title-delete btn btn-danger">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>`
                        } else {
                            templateS += `
                        <tr titleId="${contenido.id_contenido}">
                                    <td>${contenido.id_contenido}</td>
                                    <td>${contenido.region}</td>
                                    <td>${contenido.genero}</td>
                                    <td><a href="#" class="contenidoItem">${contenido.titulo}</a></td>
                                    <td>${contenido.duracion}</td>
                                    <td>
                                        <button class="title-delete btn btn-danger">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>`
                        }
                    });
                    $('#peliculas').html(templateP);
                    $('#series').html(templateS);
                }
            })

        } 
    });

    $('#titulo').keyup(function(e){
        if($('#titulo').val()){
            let titulo = $('#titulo').val();
            
            $.ajax({
                url: 'backend/product-single-by-name.php',
                type: 'GET', 
                data: {titulo},
                success: function(response){
                    let res =JSON.parse(response);
                    let valido = '';
                    valido += `
                        <li style="list-style: none;">status: ${res.status}</li>
                        <li style="list-style: none;">message: ${res.message}</li>
                    `;
    
                    $('#container').html(valido);
                    //console.log(container);
                    $('#title-result').show();
                }
    
            });
        }
    });

    $(document).on('blur', '.validar', function(){

        entrar = false;

        let region = $('#region').val(); 
        let genero = $('#genero').val();
        let titulo = $('#titulo').val();
        let duracion = $('#duracion').val();
 
        regexHora = /^(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d$/;
        let alert=" "
 
        //Titulo
        if(titulo.length < 1){
         alert += "Se requiere ingresar un nombre <br/>";
         entrar = true;
     }
 
     //Region
     if(region.length < 1){
         alert += "Se requiere ingresar la region <br/>";
         entrar = true;
     }

     //Genero
     if(genero.length < 1){
         alert += "Se requiere ingresar un genero <br/>";
         entrar = true;
     }

     //Duracion
     if(duracion.length < 1){
         alert += "Se requiere ingresar la duracion <br/>";
         entrar = true;
     }
     if(!regexHora.test(duracion)){
        alert +="La duracion debe ser HH:MM:SS <br/>"
        entrar = true;
    }

     if(entrar){
        $('#container').html(alert);
        $('#title-result').show();
    }
    });

    $('#title-form').submit(function(e){

        if(entrar){
            $('#container').html('Llena los campos');
        $('#title-result').show();
        }
         else {
        //console.log('submiting');
        const postData = {
            titulo: $('#titulo').val(),
            tipo: $('#tipo').val(),  
            region: $('#region').val(),
            genero: $('#genero').val(),
            duracion: $('#duracion').val(),
            id: $('#titleId').val()
        };
        let url = edit === false ? 'backend/product-add.php' : 'backend/product-edit.php';
        productoJSON = JSON.stringify(postData, null, 2);

        $.post(url, productoJSON, function (response) {
           console.log(response);
           edit = false;
            let res = JSON.parse(response);
            let template_bar = '';
            template_bar += `
                        <li style="list-style: none;">status: ${res.status}</li>
                        <li style="list-style: none;">message: ${res.message}</li>
                    `;
            $('#container').html(template_bar);
            $('#title-result').show();
            fetchProductos();
            $('#title-form').trigger('reset');
        });
     }
        e.preventDefault();
    });

    function fetchProductos(){
        $.ajax({
            url: 'backend/product-list.php',
            type: 'GET',
            success: function (response) {
                //console.log(response);
                let contenidos = JSON.parse(response);
                let templateP = '';
                let templateS = '';
                contenidos.forEach(contenido => {
                    if (contenido.tipo === 'pelicula') {
                        templateP += `
                        <tr titleId="${contenido.id_contenido}">
                                    <td>${contenido.id_contenido}</td>
                                    <td>${contenido.region}</td>
                                    <td>${contenido.genero}</td>
                                    <td><a href="#" class="contenidoItem">${contenido.titulo}</a></td>
                                    <td>${contenido.duracion}</td>
                                    <td>
                                        <button class="title-delete btn btn-danger">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>`
                    } else {
                        templateS += `
                    <tr titleId="${contenido.id_contenido}">
                                <td>${contenido.id_contenido}</td>
                                <td>${contenido.region}</td>
                                <td>${contenido.genero}</td>
                                <td><a href="#" class="contenidoItem">${contenido.titulo}</a></td>
                                <td>${contenido.duracion}</td>
                                <td>
                                    <button class="title-delete btn btn-danger">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>`
                    }
                });
                $('#peliculas').html(templateP);
                $('#series').html(templateS);
            }
        })
    }

    $(document).on('click', '.title-delete', function(){
        
        if(confirm('Quieres elimnar el producto?')) {
            //console.log('click');
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('titleId');
        //console.log(id);
       $.post('backend/product-delete.php', {id}, function (response) {
        console.log(response);
        fetchProductos();
        let res = JSON.parse(response);
        let template_bar = '';
            template_bar += `
                        <li style="list-style: none;">status: ${res.status}</li>
                        <li style="list-style: none;">message: ${res.message}</li>
                    `;
            $('#container').html(template_bar);
            $('#title-result').show();
       })
        }

    });

    $(document). on('click', '.contenidoItem', function(){
       //console.log('Editar');
       let element = $(this)[0].parentElement.parentElement;
       //console.log(element);
       let id = $(element).attr('titleId');
       //console.log(id);
       $.post('backend/product-single.php', {id}, function(response) {
        //console.log(response);
        const titulo = JSON.parse(response);
        //console.log(producto);
        $('#titulo').val(titulo[0].titulo);
        $('#titleId').val(titulo[0].id_contenido);
        $('#tipo').val(titulo[0].tipo);
        $('#region').val(titulo[0].region);
        $('#duracion').val(titulo[0].duracion);
        $('#genero').val(titulo[0].genero);
        edit = true;
       })
    });
});