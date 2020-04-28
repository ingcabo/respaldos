// JavaScript source code
$(document).ready(function () {
    $("#btnTexto").click(function () {
      
        var texto = $("p").text();
        alert(texto);

    });
    $("#btnHtml").click(function () {
        var texto = $("p").html();
        alert(texto);
    });

    $("#btnatributo").click(function () {
       
        alert($("#lap").attr("title"));
    });

    $("#btnsettext").click(function () {

        $("#lap").hide("slow", function () {
            var  texto = $("#lap").text();
            
            $("input").val(texto);
            $("#lap").text("Asignado por otro boton").show("3000").animate({
                fontSize:"30",left:'500px'
            }, "slow");
            $("#lap").append("texto Insertado");
            $("#lap").append("Antes de ");
        });
        
    });

    $("#btnsetclall").click(function () {
        $("#lap").text(function (i, viejo) {
            return "Texto Viejo: " + viejo + "Texto nuevo es esteve! con Index " + i;
        })
    })


    $("#btnsetAtributo").click(function () {
        $("a").attr({
            "href": "http://www.gmail.com",
            "tittle": "Pa gmail me voy"
        }
            );
        $("a").html("<b>Mejor ir a Gmail<b/>");
    });

    $("#btnAntes").click(function () {
        $("#btnTexto").before(function () {
            return "<a href='http://wwww.hotmail.com'>a Hotmail</a>";
        })

      
    });

    $("#btnEliminar").click(function () {
        
        $("div").remove(".test");
        alert("eliminado");
    });

    $("#btnClases").click(function () {

        $("h1,imput").addClass("blue");
            
    });

    $("#btnToggleClases").click(function () {

        $("h1,imput").toggleClass("blue");
    });

    $("#btnDelClases").click(function () {

        $("h1,imput").removeClass("blue");
    });


    $("#btnWidth").click(function () {
        var txt = "";
        txt += "Width: " + $("#div1").width() + "</br>";
        txt += "Height: " + $("#div1").height();
        $("#div1").html(txt);
    });

    $("#btnParents").click(function () {
        $("#test").parent().hide("slow");
        
    });


    
    $("#btnDes").click(function () {
        $("#div1").children("p.first").hide("slow");
        alert(txt);
    });

    $("#btnAjaxLoad").click(function () {
        $("#div1").load("http://api.geonames.org/citiesJSON?north=44.1&south=-9.9&east=-22.4&west=55.2&lang=de&username=demo",
            function (responseTxt, statusTxt, xhr) {
                if (statusTxt == "success")
                    alert("External content loaded successfully!");
                if (statusTxt == "error")
                    alert("Error: " + xhr.status + ": " + xhr.statusText);
            })

    });

    $("#btnGet").click(function () {
        $.get("http://api.geonames.org/citiesJSON?north=44.1&south=-9.9&east=-22.4&west=55.2&lang=de&username=demo",
            function (data, status) {
                alert("Data: " + data + "\nStatus: " + status);

        })
    });

   
    


    $("#btnCargar").click(function () {
        var url = 'http://localhost/NBAService/RestService.svc/players/GetAllPlayers';

        //alert(url);
       
        var txt = $.getJSON(url, function (result) {
            alert(txt);
            $.each(result, function (LastName, FirstName) {
                //$("div").append(field + " ");
                alert('hola');
            });
        });

      



    });
	
	
	    $("#btnCharlie").click(function () {
		
		 

        var url = 'http://10.0.28.100/apiRestCodeigniter/index.php/myrest/student/2';
 alert(url);    
        //alert(url);
       
	               
	   
                $.ajax({
                cache: false,
                type: "get",
                url: url,
                //data: {"email_addres":"rony_jdiaz@hotmail.com","password":"CONTRASEÑA","first_name":"JULIO","last_name":"perez","phone_number":"7871393","ip_address":"123.138.456"},
                success: function (data) {
                    
					alert('siii');                    
                },
				 error: function (xhr, ajaxOptions, thrownError) {
                alert('Error');
                
            }
                
            

            });		  

			
			});

      





});
